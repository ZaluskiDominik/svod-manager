<?php

namespace App\Service;

use App\DTO\LoginRequestDTO;
use App\Repository\CustomerEntityRepository;

class CustomerLoginService extends AbstractLoginService
{
    /** @var CustomerEntityRepository */
    private $customerEntityRepository;

    public function __construct(
        SessionUserService $sessionUserService,
        CustomerEntityRepository $customerEntityRepository
    ) {
        parent::__construct($sessionUserService);
        $this->customerEntityRepository = $customerEntityRepository;
    }

    public function login(LoginRequestDTO $loginRequest): bool
    {
        parent::login($loginRequest);

        $customer = $this->customerEntityRepository->findByEmailAndPassword(
            $loginRequest->getEmail(),
            $loginRequest->getPassword()
        );

        if ($customer === null) {
            return false;
        }

        $this->sessionUserService->storeCustomer($customer);

        return true;
    }
}
