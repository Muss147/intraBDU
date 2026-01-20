<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(columns: ['project_id', 'voter_hash'])]
class ProjectVote
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ProjectImpact $project;

    #[ORM\Column(length: 64)]
    private string $voterHash;

    #[ORM\Column]
    private \DateTimeImmutable $votedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoterHash(): ?string
    {
        return $this->voterHash;
    }

    public function setVoterHash(string $voterHash): static
    {
        $this->voterHash = $voterHash;

        return $this;
    }

    public function getVotedAt(): ?\DateTimeImmutable
    {
        return $this->votedAt;
    }

    public function setVotedAt(\DateTimeImmutable $votedAt): static
    {
        $this->votedAt = $votedAt;

        return $this;
    }

    public function getProject(): ?ProjectImpact
    {
        return $this->project;
    }

    public function setProject(?ProjectImpact $project): static
    {
        $this->project = $project;

        return $this;
    }
}