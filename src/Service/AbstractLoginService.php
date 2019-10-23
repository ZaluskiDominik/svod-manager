<?php

namespace App\Service;

use App\DTO\LoginRequestDTO;

abstract class AbstractLoginService
{
    /** @var SessionUserService */
    protected $sessionUserService;

    public function __construct(SessionUserService $sessionUserService)
    {
        $this->sessionUserService = $sessionUserService;
    }

    public function login(LoginRequestDTO $loginRequest): bool
    {
        if ($this->isAlreadyLoggedIn()) {
            return true;
        }

        return false;
    }

    protected function isAlreadyLoggedIn(): bool
    {
        return $this->sessionUserService->getUser() !== null;
    }
}
