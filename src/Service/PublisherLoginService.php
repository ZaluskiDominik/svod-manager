<?php

namespace App\Service;

use App\DTO\LoginRequestDTO;
use App\Repository\PublisherEntityRepository;

class PublisherLoginService extends AbstractLoginService
{
    /** @var PublisherEntityRepository */
    private $publisherEntityRepository;

    public function __construct(
        SessionUserService $sessionUserService,
        PublisherEntityRepository $publisherEntityRepository
    ) {
        parent::__construct($sessionUserService);
        $this->publisherEntityRepository = $publisherEntityRepository;
    }

    public function login(LoginRequestDTO $loginRequest): bool
    {
        parent::login($loginRequest);

        $publisher = $this->publisherEntityRepository->findByEmailAndPassword(
            $loginRequest->getEmail(),
            $loginRequest->getPassword()
        );

        if ($publisher === null) {
            return false;
        }

        $this->sessionUserService->storePublisher($publisher);

        return true;
    }
}
