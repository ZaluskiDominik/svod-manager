<?php

namespace App\Entity;

use App\Common\Serialization\SerializableObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Table(name="subscription")
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

        return $sub;
    }

    public function __construct()
    {
        $this->videos = new ArrayCollection();
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
        // TODO: Implement toArray() method.
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
