<?php

namespace App\Controller\API;

use App\Entity\PublisherEntity;
use App\Entity\SessionUserEntity;
use App\Exception\CustomerEmailExistsException;
use App\Service\JsonRequestParserService;
use App\Service\SessionUserService;
use App\Service\UpdateSessionPublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublisherController extends AbstractController
{
    /** @var SessionUserService */
    private $sessionUserService;

    /** @var JsonRequestParserService */
    private $jsonRequestParserService;

    /** @var UpdateSessionPublisherService */
    private $updateSessionPublisherService;

    public function __construct(
        SessionUserService $sessionUserService,
        JsonRequestParserService $jsonRequestParserService,
        UpdateSessionPublisherService $updateSessionPublisherService
    ) {
        $this->sessionUserService = $sessionUserService;
        $this->jsonRequestParserService = $jsonRequestParserService;
        $this->updateSessionPublisherService = $updateSessionPublisherService;
    }

    /** @Route("/api/publisher", methods={"GET"}) */
    public function getSessionPublisherDataAction()
    {
        $sessionsUser = $this->sessionUserService->getUser();
        if (!$sessionsUser || $sessionsUser->getUserRole() !== SessionUserEntity::PUBLISHER_ROLE) {
            return new Response('', 401);
        }

        return new JsonResponse($sessionsUser->getUser()->toArray());
    }

    /** @Route("/api/publisher", methods={"PATCH"}) */
    public function editPublisherCustomerData(Request $request)
    {
        $data = $this->jsonRequestParserService->parse($request);

        try {
            $this->updateSessionPublisherService->updateSessionPublisher(PublisherEntity::fromArray($data));
        } catch (CustomerEmailExistsException $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], 409);
        }

        return new Response(200);
    }
}
