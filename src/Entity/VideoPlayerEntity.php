<?php

namespace App\Entity;

use App\Common\Serialization\SerializableObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Table(name="video_player")
 * @ORM\Entity(repositoryClass="App\Repository\VideoPlayerEntityRepository")
 */
class VideoPlayerEntity implements JsonSerializable, SerializableObjectInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $templateEmbedCode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VideoEntity", mappedBy="videoPlayer", orphanRemoval=true)
     */
    private $videos;

    public static function fromArray(array $data)
    {
        $player = new self();
        $player->setName($data['name']);
        $player->setTemplateEmbedCode($data['templateEmbedCode'] ?? null);

        return $player;
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

    public function getTemplateEmbedCode(): ?string
    {
        return $this->templateEmbedCode;
    }

    public function setTemplateEmbedCode(?string $templateEmbedCode): self
    {
        $this->templateEmbedCode = $templateEmbedCode;

        return $this;
    }

    /**
     * @return Collection|VideoEntity[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(VideoEntity $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setVideoPlayer($this);
        }

        return $this;
    }

    public function removeVideo(VideoEntity $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getVideoPlayer() === $this) {
                $video->setVideoPlayer(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'templateEmbedCode' => $this->getTemplateEmbedCode()
        ];
    }
}
