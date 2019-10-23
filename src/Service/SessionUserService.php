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

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
        $this->startSession();
    }

    public function getUser(): ?SessionUserEntity
    {
        if (!$this->session->has(self::USER_SESS_KEY))
            return null;

        $sessionUser = unserialize($this->session->get(self::USER_SESS_KEY));
        if ($sessionUser->getUser() === null) {
            $repo = $this->getUserRepositoryForSessionUser($sessionUser);
            $sessionUser->setUser($repo->find($sessionUser->getUserId()));
        }

        return $sessionUser;
    }

    public function storeCustomer(CustomerEntity $customer)
    {
        $sessionUser = new SessionUserEntity($customer->getId(), SessionUserEntity::CUSTOMER_ROLE);
        $this->session->set('user', serialize($sessionUser));
    }

    public function storePublisher(PublisherEntity $publisher)
    {
        $sessionUser = new SessionUserEntity($publisher->getId(), SessionUserEntity::PUBLISHER_ROLE);
        $this->session->set('user', serialize($sessionUser));
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
