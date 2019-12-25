<?php

namespace App\Entity;

use App\Common\Serialization\SerializableObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JsonSerializable;

/**
 * @ORM\Table(name="subscription", uniqueConstraints={
 *      @UniqueConstraint(name="idx_publisher_sub", columns={"publisher_id", "name"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionEntityRepository")
 */
class SubscriptionEntity implements JsonSerializable, SerializableObjectInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PublisherEntity", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\VideoEntity", mappedBy="subscriptions")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PurchasedSubscriptionEntity", mappedBy="subscription", orphanRemoval=true)
     */
    private $purchasedSubscriptions;

    public static function fromArray(array $data)
    {
        $sub = (new self());
        if (isset($data['subscriptionName'])) {
            $sub->setName($data['subscriptionName']);
        }
        if (isset($data['subscriptionPrice'])) {
            $sub->setPrice($data['subscriptionPrice']);
        }
        if (isset($data['subscriptionCreatedAt'])) {
            $sub->setCreatedAt($data['subscriptionCreatedAt']);
        }

        if (isset($data['videos'])) {
            foreach ($data['videos'] as $video) {
                $sub->addVideo(VideoEntity::fromArray($video));
            }
        }

        return $sub;
    }

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->purchasedSubscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getPublisher(): ?PublisherEntity
    {
        return $this->publisher;
    }

    public function setPublisher(?PublisherEntity $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function setVideos(Collection $videos)
    {
        $this->videos = new ArrayCollection();
        foreach ($videos as $video) {
            $this->addVideo($video);
        }
    }

    public function addVideo(VideoEntity $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->addSubscription($this);
        }

        return $this;
    }

    public function removeVideo(VideoEntity $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            $video->removeSubscription($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        $sub = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'createdAt' => $this->getCreatedAt(),
            'publisher' => $this->getPublisher() !== null ? $this->getPublisher() : null,
            'videos' => []
        ];

        foreach ($this->videos as $video) {
            $sub['videos'][] = $video->toArray();
        }

        return $sub;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
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
            $purchasedSubscription->setSubscription($this);
        }

        return $this;
    }

    public function removePurchasedSubscription(PurchasedSubscriptionEntity $purchasedSubscription): self
    {
        if ($this->purchasedSubscriptions->contains($purchasedSubscription)) {
            $this->purchasedSubscriptions->removeElement($purchasedSubscription);
            // set the owning side to null (unless already changed)
            if ($purchasedSubscription->getSubscription() === $this) {
                $purchasedSubscription->setSubscription(null);
            }
        }

        return $this;
    }
}
