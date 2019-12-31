<?php

namespace App\Service;

use App\Entity\PublisherEntity;
use App\Exception\CompanyExistsException;
use App\Exception\PublisherEmailExistsException;
use Doctrine\ORM\EntityManagerInterface;

class UpdateSessionPublisherService
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

    /**
     * @throws CompanyExistsException
     * @throws PublisherEmailExistsException
     */
    public function updateSessionPublisher(PublisherEntity $updateData)
    {
        $this->throwExceptionIfPublisherEmailExists($updateData);

        if (!empty($updateData->getCompany()) && $this->checkIfCompanyAlreadyExists($updateData->getCompany())) {
            throw new CompanyExistsException($updateData->getCompany());
        }

        $sessionPublisher = $this->sessionUserService->getUser()->getUser();
        $sessionPublisher->setPasswordHash($updateData->getPasswordHash() ?? $sessionPublisher->getPasswordHash());
        $sessionPublisher->setFirstName($updateData->getFirstName() ?? $sessionPublisher->getFirstName());
        $sessionPublisher->setSurname($updateData->getSurname() ?? $sessionPublisher->getSurname());
        $sessionPublisher->setEmail($updateData->getEmail() ?? $sessionPublisher->getEmail());
        $sessionPublisher->setCompany($updateData->getCompany() ?? $sessionPublisher->getCompany());
        if ($updateData->getCompanyWebsite() === '') {
            $sessionPublisher->setCompanyWebsite(null);
        } else {
            $sessionPublisher->setCompanyWebsite(
                $updateData->getCompanyWebsite() ?? $sessionPublisher->getCompanyWebsite());
        }

        $this->em->merge($sessionPublisher);
        $this->em->flush();
    }

    private function throwExceptionIfPublisherEmailExists(PublisherEntity $updateData)
    {
        if (
            $updateData->getEmail()
            && $this->sessionUserService->getUser()->getUser()->getEmail() !== $updateData->getEmail()
            && $this->em->getRepository(PublisherEntity::class)->findByEmail($updateData->getEmail())) {
            throw new PublisherEmailExistsException($updateData->getEmail());
        }
    }

    private function checkIfCompanyAlreadyExists(string $company): bool
    {
        $queryResult = $this->em->getRepository(PublisherEntity::class)->findBy([
            'company' => $company
        ]);

        return $this->sessionUserService->getUser()->getUser()->getCompany() !== $company && count($queryResult) !== 0;
    }
}
