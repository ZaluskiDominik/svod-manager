<?php

namespace App\Worker;

use App\Common\Event\EventExchange;
use App\Common\Event\EventQueue;
use App\Common\Event\EventWrapper;
use App\Common\Event\SendMailEvent;
use App\Service\MailService;
use Psr\Container\ContainerInterface;
use Swift_Message;

class SendMailWorker extends AbstractWorker
{
    /** @var MailService */
    private $mailService;

    public function __construct(
        ContainerInterface $container,
        EventExchange $exchange,
        EventQueue $queue,
        array $events
    ) {
        parent::__construct($exchange, $queue, $events);
        $this->mailService = $container->get('MailService');
    }

    public function work(EventWrapper $eventWrapper)
    {
        if ($eventWrapper->getEventClass() !== SendMailEvent::class) {
            return;
        }
        $event = SendMailEvent::fromArray($eventWrapper->getEventDataArray());

        $msg = (new Swift_Message())
            ->setSubject($event->getSubject())
            ->setFrom($event->getFrom())
            ->setTo($event->getTo())
            ->setBody($event->getBody());

        $this->mailService->send($msg);
    }
}
