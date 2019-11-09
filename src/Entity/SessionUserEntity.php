<?php

namespace App\Entity;

use App\Exception\InvalidUserException;
use App\Exception\InvalidUserRoleException;

class SessionUserEntity
{
    const CUSTOMER_ROLE = 'customer';
    const PUBLISHER_ROLE = 'publisher';

    /** @var int */
    private $userId;

    /** @var string */
    private $userRole;

    /** @var CustomerEntity|PublisherEntity */
    private $user;

    public function __construct(int $userId, string $userRole)
    {
        $this->setUserId($userId);
        $this->setUserRole($userRole);
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserRole(string $role): self
    {
        if ($role !== self::CUSTOMER_ROLE && $role !== self::PUBLISHER_ROLE) {
            throw new InvalidUserRoleException($role);
        }
        $this->userRole = $role;

        return $this;
    }

    public function getUserRole(): string
    {
        return $this->userRole;
    }

    public function setUser($user): self
    {
        if ( !($user instanceof CustomerEntity) && !($user instanceof PublisherEntity) ) {
            throw new InvalidUserException();
        }
        $this->user = $user;

        return $this;
    }

    /** @Returns CustomerEntity|PublisherEntity */
    public function getUser()
    {
        return $this->user;
    }
}
