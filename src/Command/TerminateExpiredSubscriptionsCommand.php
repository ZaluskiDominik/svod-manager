<?php

namespace App\Command;

use App\Repository\PurchasedSubscriptionEntityRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TerminateExpiredSubscriptionsCommand extends Command
{
    protected static $defaultName = 'app:job:terminate-subscriptions';

    /** @var PurchasedSubscriptionEntityRepository */
    private $purchasedSubscriptionRepository;

    public function __construct(PurchasedSubscriptionEntityRepository $purchasedSubscriptionRepository)
    {
        parent::__construct();
        $this->purchasedSubscriptionRepository = $purchasedSubscriptionRepository;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->lte('activeTo', new DateTime()));
        $subs = $this->purchasedSubscriptionRepository->matching($criteria);
        foreach ($subs as $sub) {
            $this->purchasedSubscriptionRepository->remove($sub->getId());
        }
    }
}
