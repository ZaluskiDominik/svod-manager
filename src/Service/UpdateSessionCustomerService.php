<?php

namespace App\Service;

use App\Entity\CustomerEntity;
use App\Exception\CustomerEmailExistsException;
use Doctrine\ORM\EntityManagerInterface;

class UpdateSessionCustomerService
{
    /** @var SessionUserService */
    private $sessionUserService;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(SessionUserService $sessionUserService, EntityManagerInterface $em)
    {
        $this->sessionUserService = $sessionUserService;
        $this->em = $em;
    }

    public function updateSessionCustomer(CustomerEntity $updateData)
    {
        $this->throwExceptionIfCustomerEmailExists($updateData);

        $sessionCustomer = $this->sessionUserService->getUser()->getUser();
        $sessionCustomer->setPasswordHash($updateData->getPasswordHash() ?? $sessionCustomer->getPasswordHash());
        $sessionCustomer->setFirstName($updateData->getFirstName() ?? $sessionCustomer->getFirstName());
        $sessionCustomer->setSurname($updateData->getSurname() ?? $sessionCustomer->getSurname());
        $sessionCustomer->setEmail($updateData->getEmail() ?? $sessionCustomer->getEmail());

        $this->em->merge($sessionCustomer);
        $this->em->flush();
    }

    private function throwExceptionIfCustomerEmailExists(CustomerEntity $updateData)
    {
        if (
            $updateData->getEmail()
            && $this->sessionUserService->getUser()->getUser()->getEmail() !== $updateData->getEmail()
            && $this->em->getRepository(CustomerEntity::class)->findByEmail($updateData->getEmail())) {
            throw new CustomerEmailExistsException($updateData->getEmail());
        }
    }
}
