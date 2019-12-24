<?php

namespace App\Entity;

use App\Common\Serialization\SerializableObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JsonSerializable;

/**
 * @ORM\Table(name="publisher", uniqueConstraints={
 *      @UniqueConstraint(name="idx_p_email", columns={"email"}),
 *      @UniqueConstraint(name="idx_company", columns={"company"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\PublisherEntityRepository")
 */
class PublisherEntity implements SerializableObjectInterface, JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
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
     * @ORM\Column(type="string", length=60)
     */
    private $passwordHash;

    /**
     * @ORM\Column(type="float")
     */
    private $accountBalance;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $companyWebsite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SubscriptionEntity", mappedBy="publisher", orphanRemoval=true)
     */
    private $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VideoEntity", mappedBy="publisher", cascade={"persist"})
     */
    private $videos;

    public static function fromArray(array $data): self
    {
        $publisher = new self();
        if (isset($data['firstName'])) {
            $publisher->setFirstName($data['firstName']);
        }
        if (isset($data['surname'])) {
            $publisher->setSurname($data['surname']);
        }
        if (isset($data['email'])) {
            $publisher->setEmail($data['email']);
        }
        if (isset($data['accountBalance'])) {
            $publisher->setAccountBalance($data['accountBalance']);
        }
        if (isset($data['company'])) {
            $publisher->setCompany($data['company']);
        }
        if (isset($data['companyWebsite'])) {
            $publisher->setCompanyWebsite($data['companyWebsite']);
        }
        if (isset($data['password'])) {
            $publisher->setPasswordHash(password_hash($data['password'], PASSWORD_DEFAULT));
        } else if (isset($data['passwordHash'])) {
            $publisher->setPasswordHash($data['passwordHash']);
        }

        return $publisher;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'surname' => $this->getSurname(),
            'email' => $this->getEmail(),
            'accountBalance' => $this->getAccountBalance(),
            'passwordHash' => $this->getPasswordHash(),
            'company' => $this->getCompany(),
            'companyWebsite' => $this->getCompanyWebsite()
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
        $this->videos = new ArrayCollection();
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

    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    public function getAccountBalance(): ?float
    {
        return $this->accountBalance;
    }

    public function setAccountBalance(float $accountBalance): self
    {
        $this->accountBalance = $accountBalance;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCompanyWebsite(): ?string
    {
        return $this->companyWebsite;
    }

    public function setCompanyWebsite(?string $companyWebsite): self
    {
        $this->companyWebsite = $companyWebsite;

        return $this;
    }

    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(SubscriptionEntity $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setPublisher($this);
        }

        return $this;
    }

    public function removeSubscription(SubscriptionEntity $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getPublisher() === $this) {
                $subscription->setPublisher(null);
            }
        }

        return $this;
    }

    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(VideoEntity $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setPublisher($this);
        }

        return $this;
    }

    public function removeVideo(VideoEntity $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getPublisher() === $this) {
                $video->setPublisher(null);
            }
        }

        return $this;
    }
}
