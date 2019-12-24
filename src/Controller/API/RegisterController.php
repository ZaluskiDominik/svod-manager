<?php

namespace App\Controller\API;

use App\Entity\CustomerEntity;
use App\Entity\PublisherEntity;
use App\Exception\CompanyExistsException;
use App\Exception\CustomerEmailExistsException;
use App\Exception\PublisherEmailExistsException;
use App\Service\CustomerRegisterService;
use App\Service\JsonRequestParserService;
use App\Service\PublisherRegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /** @var PublisherRegisterService */
    private $publisherRegisterService;

    /** @var CustomerRegisterService */
    private $customerRegisterService;

    /** @var JsonRequestParserService */
    private $jsonRequestParserService;

    public function __construct(
        PublisherRegisterService $publisherRegisterService,
        CustomerRegisterService $customerRegisterService,
        JsonRequestParserService $jsonRequestParserService
    ) {
        $this->publisherRegisterService = $publisherRegisterService;
        $this->customerRegisterService = $customerRegisterService;
        $this->jsonRequestParserService = $jsonRequestParserService;
    }

    /** @Route("/api/publisher", methods={"POST"}) */
    public function registerPublisherAction(Request $request)
    {
        $data = $this->jsonRequestParserService->parse($request);

        try {
            $this->publisherRegisterService->register(PublisherEntity::fromArray($data));
        } catch (PublisherEmailExistsException | CompanyExistsException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'errorField' => ($e instanceof CompanyExistsException) ? 'company' : 'email'
            ], 409);
        }

        return new JsonResponse([ 'redirectUrl' => '/dashboard' ], 201);
    }

    /** @Route("/api/customer", methods={"POST"}) */
    public function registerCustomerAction(Request $request)
    {
        $data = $this->jsonRequestParserService->parse($request);

        try {
            $this->customerRegisterService->register(CustomerEntity::fromArray($data));
        } catch (CustomerEmailExistsException $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'errorField' => 'email'
            ], 409);
        }

        return new JsonResponse([ 'redirectUrl' => '/dashboard' ], 201);
    }
}
