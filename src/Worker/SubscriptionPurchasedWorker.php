<?php

namespace App\Worker;

use App\Common\Event\EventExchange;
use App\Common\Event\EventQueue;
use App\Common\Event\EventSender;
use App\Common\Event\EventWrapper;
use App\Common\Event\SendMailEvent;
use App\Common\Event\SubscriptionPurchasedEvent;
use App\Repository\PurchasedSubscriptionEntityRepository;
use Psr\Container\ContainerInterface;

class SubscriptionPurchasedWorker extends AbstractWorker
{
    /** @var EventSender */
    private $eventSender;

    /** @var PurchasedSubscriptionEntityRepository */
    private $purchasedSubscriptionRepository;

    public function __construct(
        ContainerInterface $container,
        EventExchange $exchange,
        EventQueue $queue,
        array $events
    ) {
        parent::__construct($exchange, $queue, $events);
        $this->eventSender = $container->get('EventSender');
        $this->purchasedSubscriptionRepository = $container->get('PurchasedSubscriptionRepository');
    }

    public function work(EventWrapper $eventWrapper)
    {
        if ($eventWrapper->getEventClass() !== SubscriptionPurchasedEvent::class) {
            return;
        }
        $event = SubscriptionPurchasedEvent::fromArray($eventWrapper->getEventDataArray());

        $purchasedSub = $this->purchasedSubscriptionRepository->findOneBy([
            'customer' => $event->getCustomerId(),
            'subscription' => $event->getSubscriptionId()
        ]);
        $sub = $purchasedSub->getSubscription();
        $customer = $purchasedSub->getCustomer();
        $publisher = $sub->getPublisher();

        $sendMailEvent = new SendMailEvent(
            $customer->getEmail(),
            'Thank you for subscribing to "' . $sub->getName() . '"',
            'Hello ' . $customer->getFirstName() . ' ' . $customer->getSurname() . ',<br>
            Thank you for subscribing to <b>' . $sub->getName() . '</b>. You can access all videos that belongs
            to this subscription. If you want more you can always check other offers of <b>' . $publisher->getCompany() . '</b>.
            <br>On behalf of ' . $publisher->getCompany() . ',<br>SVOD Manager'
        );

        $this->eventSender->send(new EventExchange(EventExchange::MAILS), $sendMailEvent);
    }
}
