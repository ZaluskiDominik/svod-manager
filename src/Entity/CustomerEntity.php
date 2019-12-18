<?php

namespace App\Entity;

use App\Common\Serialization\SerializableObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="App\Repository\CustomerEntityRepository")
 */
class CustomerEntity implements SerializableObjectInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, name="first_name")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60, name="password_hash")
     */
    private $passwordHash;

    /**
     * @ORM\Column(type="float", name="account_balance")
     */
    private $accountBalance;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PurchasedSubscriptionEntity", mappedBy="customer", orphanRemoval=true)
     */
    private $purchasedSubscriptions;

    public function __construct()
    {
        $this->purchasedSubscriptions = new ArrayCollection();
    }

    public static function fromArray(array $data): self
    {
        $customer = new self();
        if (isset($data['firstName'])) {
            $customer->setFirstName($data['firstName']);
        }
        if (isset($data['surname'])) {
            $customer->setSurname($data['surname']);
        }
        if (isset($data['email'])) {
            $customer->setEmail($data['email']);
        }
        if (isset($data['accountBalance'])) {
            $customer->setAccountBalance($data['accountBalance']);
        }
        if (isset($data['password'])) {
            $customer->setPasswordHash(password_hash($data['password'], PASSWORD_DEFAULT));
        } else if (isset($data['passwordHash'])) {
            $customer->setPasswordHash($data['passwordHash']);
        }

        return $customer;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'surname' => $this->getSurname(),
            'email' => $this->getEmail(),
            'accountBalance' => $this->getAccountBalance(),
            'passwordHash' => $this->getPasswordHash()
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->passwordHash = $password_hash;

        return $this;
    }

    public function getAccountBalance(): ?string
    {
        return $this->accountBalance;
    }

    public function setAccountBalance(string $accountBalance): self
    {
        $this->accountBalance = $accountBalance;

        return $this;
    }

    /**
     * @return Collection|PurchasedSubscriptionEntity[]
     */
    public function getPurchasedSubscriptions(): Collection
    {
        return $this->purchasedSubscriptions;
    }

    public function addPurchasedSubscription(PurchasedSubscriptionEntity $purchasedSubscription): self
    {
        if (!$this->purchasedSubscriptions->contains($purchasedSubscription)) {
            $this->purchasedSubscriptions[] = $purchasedSubscription;
            $purchasedSubscription->setCustomer($this);
        }

        return $this;
    }

    public function removePurchasedSubscription(PurchasedSubscriptionEntity $purchasedSubscription): self
    {
        if ($this->purchasedSubscriptions->contains($purchasedSubscription)) {
            $this->purchasedSubscriptions->removeElement($purchasedSubscription);
            // set the owning side to null (unless already changed)
            if ($purchasedSubscription->getCustomer() === $this) {
                $purchasedSubscription->setCustomer(null);
            }
        }

        return $this;
    }
}
