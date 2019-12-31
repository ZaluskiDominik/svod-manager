<?php

namespace App\Controller\API;

use App\Entity\CustomerEntity;
use App\Entity\SessionUserEntity;
use App\Exception\CustomerEmailExistsException;
use App\Repository\VideoEntityRepository;
use App\Service\JsonRequestParserService;
use App\Service\SessionUserService;
use App\Service\UpdateSessionCustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /** @var SessionUserService */
    private $sessionUserService;

    /** @var JsonRequestParserService */
    private $jsonRequestParserService;

    /** @var UpdateSessionCustomerService */
    private $updateSessionCustomerService;

    /** @var VideoEntityRepository */
    private $videosRepository;

    public function __construct(
        SessionUserService $sessionUserService,
        JsonRequestParserService $jsonRequestParserService,
        UpdateSessionCustomerService $updateSessionCustomerService,
        VideoEntityRepository $videosRepository
    ) {
        $this->sessionUserService = $sessionUserService;
        $this->jsonRequestParserService = $jsonRequestParserService;
        $this->updateSessionCustomerService = $updateSessionCustomerService;
        $this->videosRepository = $videosRepository;
    }

    /** @Route("/api/customer", methods={"GET"}) */
    public function getSessionCustomerDataAction()
    {
        if (!$this->sessionUserService->hasSessionCustomer()) {
            return new Response('', 401);
        }
        $sessionUser = $this->sessionUserService->getUser();

        return new JsonResponse($sessionUser->getUser()->toArray());
    }

    /** @Route("/api/customer", methods={"PATCH"}) */
    public function editSessionCustomerData(Request $request)
    {
        if (!$this->sessionUserService->hasSessionCustomer()) {
            return new Response('', 401);
        }
        $data = $this->jsonRequestParserService->parse($request);

        try {
            $this->updateSessionCustomerService->updateSessionCustomer(CustomerEntity::fromArray($data));
        } catch (CustomerEmailExistsException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'errorField' => 'email'
            ], 409);
        }

        return new Response(200);
    }

    /** @Route("/api/customer/videos", methods={"GET"}) */
    public function getCustomersVideos()
    {
        if (!$this->sessionUserService->hasSessionCustomer()) {
            return new Response('', 401);
        }
        $customer = $this->sessionUserService->getUser()->getUser();

        return new JsonResponse([
            'videos' => $this->videosRepository->findCustomerVideos($customer->getId())
        ]);
    }
}
