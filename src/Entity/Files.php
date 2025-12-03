<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FilesRepository;

#[ORM\Entity(repositoryClass: FilesRepository::class)]
class Files extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $size = null;

    #[ORM\ManyToOne(inversedBy: 'fichiers')]
    private ?Alertes $alertes = null;

    #[ORM\ManyToOne(inversedBy: 'pieceJointe')]
    private ?Incidents $incidents = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityLibelle(): ?string
    {
        return $this->filename;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getAlertes(): ?Alertes
    {
        return $this->alertes;
    }

    public function setAlertes(?Alertes $alertes): static
    {
        $this->alertes = $alertes;

        return $this;
    }

    public function getIncidents(): ?Incidents
    {
        return $this->incidents;
    }

    public function setIncidents(?Incidents $incidents): static
    {
        $this->incidents = $incidents;

        return $this;
    }
}
