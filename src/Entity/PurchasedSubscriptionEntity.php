<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchasedSubscriptionEntityRepository")
 * @ORM\Table(name="purchased_subscription")
 */
class PurchasedSubscriptionEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="date", name="start_date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date", name="active_to")
     */
    private $activeTo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SubscriptionEntity", inversedBy="purchasedSubscriptions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $subscription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CustomerEntity", inversedBy="purchasedSubscriptions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getActiveTo(): ?\DateTimeInterface
    {
        return $this->activeTo;
    }

    public function setActiveTo(\DateTimeInterface $activeTo): self
    {
        $this->activeTo = $activeTo;

        return $this;
    }

    public function getSubscription(): ?SubscriptionEntity
    {
        return $this->subscription;
    }

    public function setSubscription(?SubscriptionEntity $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getCustomer(): ?CustomerEntity
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerEntity $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
