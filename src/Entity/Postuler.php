<?php

namespace App\Entity;

use App\Repository\PostulerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostulerRepository::class)]
class Postuler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomAgent = null;

    #[ORM\Column(length: 255)]
    private ?string $posteOccupe = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matricule = null;

    #[ORM\ManyToOne(inversedBy: 'postulants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffresEmploi $offre = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Files $cv = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motivations = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $validated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAgent(): ?string
    {
        return $this->nomAgent;
    }

    public function setNomAgent(string $nomAgent): static
    {
        $this->nomAgent = $nomAgent;

        return $this;
    }

    public function getPosteOccupe(): ?string
    {
        return $this->posteOccupe;
    }

    public function setPosteOccupe(string $posteOccupe): static
    {
        $this->posteOccupe = $posteOccupe;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getOffre(): ?OffresEmploi
    {
        return $this->offre;
    }

    public function setOffre(?OffresEmploi $offre): static
    {
        $this->offre = $offre;

        return $this;
    }

    public function getCv(): ?Files
    {
        return $this->cv;
    }

    public function setCv(?Files $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getMotivations(): ?string
    {
        return $this->motivations;
    }

    public function setMotivations(string $motivations): static
    {
        $this->motivations = $motivations;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(?bool $validated): static
    {
        $this->validated = $validated;

        return $this;
    }
}
