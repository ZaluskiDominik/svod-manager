<?php

namespace App\Service;

use App\Entity\CustomerEntity;
use App\Exception\CustomerEmailExistsException;
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

    /** @throws CustomerEmailExistsException */
    public function register(CustomerEntity $customerEntity)
    {
        if ($this->checkIfCustomerAlreadyExists($customerEntity)) {
            throw new CustomerEmailExistsException($customerEntity->getEmail());
        }

        $customerEntity->setAccountBalance($this->defaultAccountBalance);
        $this->em->persist($customerEntity);
        $this->em->flush();

        $this->sessionUserService->storeCustomer($customerEntity);
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
