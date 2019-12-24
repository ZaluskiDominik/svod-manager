<?php

namespace App\Service;

use App\Entity\PublisherEntity;
use App\Exception\CompanyExistsException;
use App\Exception\PublisherEmailExistsException;
use App\Repository\PublisherEntityRepository;
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

    /**
     * @throws PublisherEmailExistsException
     * @throws CompanyExistsException
     */
    public function register(PublisherEntity $publisherEntity)
    {
        if ($this->checkIfPublisherAlreadyExists($publisherEntity)) {
            throw new PublisherEmailExistsException($publisherEntity->getEmail());
        }

        if ($this->checkIfCompanyAlreadyExists($publisherEntity->getCompany())) {
            throw new CompanyExistsException($publisherEntity->getCompany());
        }

        if ($publisherEntity->getCompanyWebsite() === '') {
            $publisherEntity->setCompanyWebsite(null);
        }
        $publisherEntity->setAccountBalance($this->defaultAccountBalance);
        $this->em->persist($publisherEntity);
        $this->em->flush();

        $this->sessionUserService->storePublisher($publisherEntity);
    }

    private function checkIfPublisherAlreadyExists(PublisherEntity $publisherEntity): bool
    {
        $publisherRepository = $this->em->getRepository(PublisherEntity::class);
        $queryResult = $publisherRepository->findBy([
            'email' => $publisherEntity->getEmail()
        ]);

        return count($queryResult) !== 0;
    }

    private function checkIfCompanyAlreadyExists(string $company): bool
    {
        $queryResult = $this->em->getRepository(PublisherEntity::class)->findBy([
            'company' => $company
        ]);

        return count($queryResult) !== 0;
    }
}
