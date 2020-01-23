<?php

namespace App\Common\Event;

class SubscriptionPurchasedEvent extends AbstractEvent
{
    /** @var int */
    private $subscriptionId;

    /** @var int */
    private $customerId;

    public function __construct(int $subscriptionId, int $customerId)
    {
        $this->subscriptionId = $subscriptionId;
        $this->customerId = $customerId;
    }

    public static function fromArray(array $data)
    {
        return new self($data['subscriptionId'], $data['customerId']);
    }

    public function toArray(): array
    {
        return [
            'customerId' => $this->getCustomerId(),
            'subscriptionId' => $this->getSubscriptionId()
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getSubscriptionId(): int
    {
        return $this->subscriptionId;
    }

    public function setSubscriptionId(int $subscriptionId): void
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }
}
