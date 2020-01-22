<?php

use App\Common\Event\EventQueue;
use App\Worker\SubscriptionCreatedWorker;

return [
    [
        'worker' => SubscriptionCreatedWorker::class,
        'queue' => EventQueue::SUBSCRIPTION
    ]
];
