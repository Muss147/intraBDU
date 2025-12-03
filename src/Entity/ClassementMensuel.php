<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\ClassementMensuelRepository;

#[ORM\Entity(repositoryClass: ClassementMensuelRepository::class)]
class ClassementMensuel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $mois;

    #[ORM\ManyToOne]
    private ?Agents $agent = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float $moyenne;

    #[ORM\Column(type: Types::INTEGER)]
    private int $nombreVotes;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois(): ?\DateTime
    {
        return $this->mois;
    }

    public function setMois(\DateTime $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    public function setMoyenne(float $moyenne): static
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    public function getNombreVotes(): ?int
    {
        return $this->nombreVotes;
    }

    public function setNombreVotes(int $nombreVotes): static
    {
        $this->nombreVotes = $nombreVotes;

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

    public function getAgent(): ?Agents
    {
        return $this->agent;
    }

    public function setAgent(?Agents $agent): static
    {
        $this->agent = $agent;

        return $this;
    }
}
