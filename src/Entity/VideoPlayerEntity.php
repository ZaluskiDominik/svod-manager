<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="video_player")
 * @ORM\Entity(repositoryClass="App\Repository\VideoPlayerEntityRepository")
 */
class VideoPlayerEntity
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
}
