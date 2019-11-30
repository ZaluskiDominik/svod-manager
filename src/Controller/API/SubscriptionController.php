<?php

namespace App\Controller\API;

use App\Entity\SubscriptionEntity;
use App\Entity\VideoEntity;
use App\Service\JsonRequestParserService;
use App\Service\SessionUserService;
use Doctrine\ORM\EntityManagerInterface;
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

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        SessionUserService $sessionUserService,
        JsonRequestParserService $jsonRequestParserService,
        EntityManagerInterface $em
    ) {
        $this->sessionUserService = $sessionUserService;
        $this->jsonRequestParserService = $jsonRequestParserService;
        $this->em = $em;
    }

    /** @Route("/api/subscription", methods={"POST"}) */
    public function createSubscriptionAction(Request $request)
    {
        if (!$this->sessionUserService->hasSessionPublisher()) {
            return new Response('', 401);
        }

        $data = $this->jsonRequestParserService->parse($request);

        $sub = SubscriptionEntity::fromArray($data);
        $sub->setCreatedAt(new \DateTime());
        $sub->setPublisher($this->sessionUserService->getUser()->getUser());
        foreach ($data['videos'] as $video) {
            $video = VideoEntity::fromArray($video);
            $video->setPublisher($this->sessionUserService->getUser()->getUser());
            $sub->addVideo($video);
        }
        $this->em->persist($sub);
        $this->em->flush();

        return new JsonResponse($sub, 201);
    }
}
