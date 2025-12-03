<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\VoteNoteRepository;

#[ORM\Entity(repositoryClass: VoteNoteRepository::class)]
class VoteNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Parametres $critere = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $note;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    private ?Votes $vote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCritere(): ?Parametres
    {
        return $this->critere;
    }

    public function setCritere(?Parametres $critere): static
    {
        $this->critere = $critere;

        return $this;
    }

    public function getVote(): ?Votes
    {
        return $this->vote;
    }

    public function setVote(?Votes $vote): static
    {
        $this->vote = $vote;

        return $this;
    }
}
