<?php

namespace App\Entity;

use App\Common\Serialization\SerializableObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JsonSerializable;

/**
 * @ORM\Table(name="video", indexes={
 *      @Index(name="idx_fulltext_title", columns={"title"}, flags={"fulltext"})
 * }, uniqueConstraints={
 *      @UniqueConstraint(name="idx_publisher_title", columns={"publisher_id", "title"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\VideoEntityRepository")
 */
class VideoEntity implements JsonSerializable, SerializableObjectInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title;

    /**
     * @ORM\Column(type="text", name="embed_code")
     */
    private $embedCode;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=200, name="poster_url")
     */
    private $posterUrl;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SubscriptionEntity", inversedBy="videos", cascade={"persist"})
     */
    private $subscriptions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PublisherEntity", inversedBy="videos", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VideoPlayerEntity", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $videoPlayer;

    public static function fromArray(array $data)
    {
        $video = new self();
        if (isset($data['id'])) {
            $video->setId($data['id']);
        }
        if (isset($data['title'])) {
            $video->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $video->setDescription($data['description']);
        }
        if (isset($data['posterUrl'])) {
            $video->setPosterUrl($data['posterUrl']);
        }
        if (isset($data['embedCode'])) {
            $video->setEmbedCode($data['embedCode']);
        }

        return $video;
    }

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEmbedCode(): ?string
    {
        return $this->embedCode;
    }

    public function setEmbedCode(string $embedCode): self
    {
        $this->embedCode = $embedCode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosterUrl(): ?string
    {
        return $this->posterUrl;
    }

    public function setPosterUrl(string $posterUrl): self
    {
        $this->posterUrl = $posterUrl;

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
        }

        return $this;
    }

    public function removeSubscription(SubscriptionEntity $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
        }

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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'publisherId' => ($this->getPublisher()) ? $this->getPublisher()->getId() : null,
            'publisherCompany' => ($this->getPublisher()) ? $this->getPublisher()->getCompany() : null,
            'posterUrl' => $this->getPosterUrl(),
            'embedCode' => $this->getEmbedCode(),
            'videoPlayer' => $this->getVideoPlayer()
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getVideoPlayer(): ?VideoPlayerEntity
    {
        return $this->videoPlayer;
    }

    public function setVideoPlayer(?VideoPlayerEntity $videoPlayer): self
    {
        $this->videoPlayer = $videoPlayer;

        return $this;
    }
}
