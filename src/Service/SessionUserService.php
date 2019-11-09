<?php

namespace App\Service;

use App\Entity\CustomerEntity;
use App\Entity\PublisherEntity;
use App\Entity\SessionUserEntity;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionUserService
{
    const USER_SESS_KEY = 'user';

    /** @var SessionInterface */
    private $session;

    /** @var EntityManagerInterface */
    private $em;

    /** @var SessionUserEntity */
    private $sessionUser;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
        $this->startSession();
    }

    public function getUser(): ?SessionUserEntity
    {
        if ($this->sessionUser !== null) {
            return $this->sessionUser;
        }

        if (!$this->session->has(self::USER_SESS_KEY)) {
            return null;
        }

        $this->sessionUser = unserialize($this->session->get(self::USER_SESS_KEY));
        if ($this->sessionUser->getUser() === null) {
            $repo = $this->getUserRepositoryForSessionUser($this->sessionUser);
            $this->sessionUser->setUser($repo->find($this->sessionUser->getUserId()));
        }

        return $this->sessionUser;
    }

    public function storeCustomer(CustomerEntity $customer)
    {
        $sessionUser = new SessionUserEntity($customer->getId(), SessionUserEntity::CUSTOMER_ROLE);
        $this->session->set(self::USER_SESS_KEY, serialize($sessionUser));
    }

    public function storePublisher(PublisherEntity $publisher)
    {
        $sessionUser = new SessionUserEntity($publisher->getId(), SessionUserEntity::PUBLISHER_ROLE);
        $this->session->set(self::USER_SESS_KEY, serialize($sessionUser));
    }

    public function removeUser()
    {
        $this->session->remove(self::USER_SESS_KEY);
        $this->sessionUser = null;
    }

    private function startSession()
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
    }

    private function getUserRepositoryForSessionUser(SessionUserEntity $sessionUser): ObjectRepository
    {
        $repoClass = ($sessionUser->getUserRole() === SessionUserEntity::PUBLISHER_ROLE)
            ? PublisherEntity::class : CustomerEntity::class;

        return $this->em->getRepository($repoClass);
    }
}
