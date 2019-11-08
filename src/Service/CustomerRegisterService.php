<?php

namespace App\Service;

use App\Entity\CustomerEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class CustomerRegisterService extends AbstractRegisterService
{
    public function __construct(
        EntityManagerInterface $em,
        SessionUserService $sessionUserService,
        ContainerBagInterface $containerBag
    ) {
        parent::__construct($em, $sessionUserService, $containerBag);
    }

    public function register(CustomerEntity $customerEntity): bool
    {
        if ($this->checkIfCustomerAlreadyExists($customerEntity)) {
            return false;
        }

        $customerEntity->setAccountBalance($this->defaultAccountBalance);
        $this->em->persist($customerEntity);
        $this->em->flush();

        $this->sessionUserService->storeCustomer($customerEntity);

        return true;
    }

    private function checkIfCustomerAlreadyExists(CustomerEntity $customerEntity): bool
    {
        $customerRepository = $this->em->getRepository(CustomerEntity::class);
        $queryResult = $customerRepository->findBy([
            'email' => $customerEntity->getEmail()
        ]);

        return count($queryResult) !== 0;
    }
}
