<?php

namespace App\Service;

use App\Entity\PublisherEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class PublisherRegisterService extends AbstractRegisterService
{
    public function __construct(
        EntityManagerInterface $em,
        SessionUserService $sessionUserService,
        ContainerBagInterface $containerBag
    ) {
        parent::__construct($em, $sessionUserService, $containerBag);
    }

    public function register(PublisherEntity $publisherEntity): bool
    {
        if ($this->checkIfPublisherAlreadyExists($publisherEntity)) {
            return false;
        }

        $publisherEntity->setAccountBalance($this->defaultAccountBalance);
        $this->em->persist($publisherEntity);
        $this->em->flush();

        $this->sessionUserService->storePublisher($publisherEntity);

        return true;
    }

    private function checkIfPublisherAlreadyExists(PublisherEntity $publisherEntity): bool
    {
        $publisherRepository = $this->em->getRepository(PublisherEntity::class);
        $queryResult = $publisherRepository->findBy([
            'email' => $publisherEntity->getEmail()
        ]);

        return count($queryResult) !== 0;
    }
}
