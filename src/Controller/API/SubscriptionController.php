<?php

namespace App\Controller\API;

use App\Entity\SubscriptionEntity;
use App\Exception\NotEnoughMoneyException;
use App\Repository\SubscriptionEntityRepository;
use App\Service\JsonRequestParserService;
use App\Service\SessionUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /** @var SessionUserService */
    private $sessionUserService;

    /** @var JsonRequestParserService */
    private $jsonRequestParserService;

    /** @var SubscriptionEntityRepository */
    private $subscriptionRepository;

    public function __construct(
        SessionUserService $sessionUserService,
        JsonRequestParserService $jsonRequestParserService,
        SubscriptionEntityRepository $subscriptionRepository
    ) {
        $this->sessionUserService = $sessionUserService;
        $this->jsonRequestParserService = $jsonRequestParserService;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /** @Route("/api/subscriptions", methods={"GET"}) */
    public function getAllSubscriptions(Request $request)
    {
        $purchasedInfoFilter = ($request->query->get('info') === 'purchased');
        if (!$purchasedInfoFilter) {
            return new JsonResponse($this->subscriptionRepository->findAllSubscriptions());
        }

        if (!$this->sessionUserService->hasSessionCustomer()) {
            return new JsonResponse([
                'message' => 'Only customer can use this option'
            ], 400);
        }
        $user = $this->sessionUserService->getUser()->getUser();

        return new JsonResponse($this->subscriptionRepository->findAllSubscriptionsWithInfoIfPurchased($user->getId()));
    }

    /** @Route("/api/subscription", methods={"POST"}) */
    public function createSubscriptionAction(Request $request)
    {
        if (!$this->sessionUserService->hasSessionPublisher()) {
            return new Response('', 401);
        }

        $data = $this->jsonRequestParserService->parse($request);

        $sub = SubscriptionEntity::fromArray($data);
        if (count($this->subscriptionRepository->findBy(['name' => $sub->getName()]))) {
            return new JsonResponse([
                'message' => 'You already have subscription with the same name'
            ], 409);
        }

        $sub->setCreatedAt(new \DateTime());
        $sub->setPublisher($this->sessionUserService->getUser()->getUser());
        $this->subscriptionRepository->createSubscription($sub);

        return new JsonResponse($sub, 201);
    }

    /** @Route("/api/subscription", methods={"DELETE"}) */
    public function deleteSubscriptionAction(Request $request)
    {
        if (!$this->sessionUserService->hasSessionPublisher()) {
            return new Response('', 401);
        }

        $data = $this->jsonRequestParserService->parse($request);
        $this->subscriptionRepository->deleteSubscription($data['id']);

        return new Response('', 200);
    }

    /** @Route("/api/subscription/purchase", methods={"POST"}) */
    public function purchaseSubscriptionAction(Request $request)
    {
        if (!$this->sessionUserService->hasSessionCustomer()) {
            return new Response('', 401);
        }

        $data = $this->jsonRequestParserService->parse($request);
        try {
            $this->subscriptionRepository->purchaseSubscription($data['subscriptionId'], $data['customerId']);
        } catch (NotEnoughMoneyException $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], 422);
        }

        return new Response('');
    }
}
