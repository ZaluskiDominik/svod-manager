<?php

use App\Common\Event\EventExchange;
use App\Common\Event\EventQueue;
use App\Common\Event\SendMailEvent;
use App\Common\Event\SubscriptionPurchasedEvent;
use App\Worker\SendMailWorker;
use App\Worker\SubscriptionPurchasedWorker;

return [
    [
        'worker' => SubscriptionPurchasedWorker::class,
        'exchange' => EventExchange::SUBSCRIPTIONS,
        'queue' => EventQueue::SUBSCRIPTIONS,
        'events' => [
            SubscriptionPurchasedEvent::class
        ]
    ],
    [
        'worker' => SendMailWorker::class,
        'exchange' => EventExchange::MAILS,
        'queue' => EventQueue::MAILS,
        'events' => [
            SendMailEvent::class
        ]
    ]
];
