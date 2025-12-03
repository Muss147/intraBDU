<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\ActualitesRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('slug', message: 'Ce slug est déjà utilisé.')]
#[ORM\Entity(repositoryClass: ActualitesRepository::class)]
class Actualites extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide.")]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Files $thumbnail = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Files $cover = null;

    #[ORM\Column(nullable: true)]
    private ?bool $online = true;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityLibelle(): ?string
    {
        return $this->titre;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getThumbnail(): ?Files
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?Files $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getCover(): ?Files
    {
        return $this->cover;
    }

    public function setCover(?Files $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(?bool $online): static
    {
        $this->online = $online;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): static
    {
        if ($this->titre) {
            $slugger = new AsciiSlugger('fr'); // Support du français
            $slug = $slugger->slug($this->titre)->lower();
            $this->slug = $slug;
        }
        return $this;
    }
}
