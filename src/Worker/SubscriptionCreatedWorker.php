<?php

namespace App\Worker;

class SubscriptionCreatedWorker extends AbstractWorker
{
    public function work(array $data)
    {
        var_dump($data);
    }
}
