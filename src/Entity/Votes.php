<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\VotesRepository;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'votesRecus')]
    private ?Agents $agent = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $votedAt;

    #[ORM\OneToMany(mappedBy: 'vote', targetEntity: VoteNote::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $notes;

    #[ORM\ManyToOne(inversedBy: 'votesEffectues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agents $votant = null;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVotedAt(): ?\DateTime
    {
        return $this->votedAt;
    }

    public function setVotedAt(\DateTime $votedAt): static
    {
        $this->votedAt = $votedAt;

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

    /**
     * @return Collection<int, VoteNote>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(VoteNote $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setVote($this);
        }

        return $this;
    }

    public function removeNote(VoteNote $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getVote() === $this) {
                $note->setVote(null);
            }
        }

        return $this;
    }

    public function getVotant(): ?Agents
    {
        return $this->votant;
    }

    public function setVotant(?Agents $votant): static
    {
        $this->votant = $votant;

        return $this;
    }

}
