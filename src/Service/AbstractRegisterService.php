<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

abstract class AbstractRegisterService
{
    /** @var int */
    protected $defaultAccountBalance;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var SessionUserService */
    protected $sessionUserService;

    public function __construct(
        EntityManagerInterface $em,
        SessionUserService $sessionUserService,
        ContainerBagInterface $containerBag
    ) {
        $this->defaultAccountBalance = $containerBag->get('register.default_account_balance');
        $this->em = $em;
        $this->sessionUserService = $sessionUserService;
    }
}
