<?php

namespace App\Controller\API;

use App\DTO\LoginRequestDTO;
use App\Service\CustomerLoginService;
use App\Service\JsonRequestParserService;
use App\Service\PublisherLoginService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /** @var CustomerLoginService */
    private $customerLoginService;

    /** @var PublisherLoginService */
    private $publisherLoginService;

    /** @var JsonRequestParserService */
    private $jsonRequestParserService;

    public function __construct(
        CustomerLoginService $customerLoginService,
        PublisherLoginService $publisherLoginService,
        JsonRequestParserService $jsonRequestParserService
    ) {
        $this->customerLoginService = $customerLoginService;
        $this->publisherLoginService = $publisherLoginService;
        $this->jsonRequestParserService = $jsonRequestParserService;
    }

    /** @Route("/api/customer/login", methods={"POST"}) */
    public function customerLoginAction(Request $request)
    {
        $data = $this->jsonRequestParserService->parse($request);

        if (!$this->customerLoginService->login(new LoginRequestDTO($data['email'], $data['password']))) {
            return new Response('', 401);
        }

        return new Response();
    }

    /** @Route("/api/publisher/login", methods={"POST"}) */
    public function publisherLoginAction(Request $request)
    {
        $data = $this->jsonRequestParserService->parse($request);

        if (!$this->publisherLoginService->login(new LoginRequestDTO($data['email'], $data['password']))) {
            return new Response('', 401);
        }

        return new Response();
    }
}
