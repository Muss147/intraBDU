<?php

namespace App\Entity;

use App\Entity\Files;
use App\Entity\Votes;
use App\Entity\Parametres;
use App\Mapping\EntityBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\AgentsRepository;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AgentsRepository::class)]
class Agents extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenoms = null;

    #[ORM\Column(length: 255)]
    private ?string $civilite = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fixe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commune = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\ManyToOne(inversedBy: 'agents')]
    private ?Parametres $service = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Files $photo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $anniversaire = null;

    #[ORM\OneToMany(mappedBy: 'agent', targetEntity: Votes::class)]
    private Collection $votes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $matricule = null;

    #[ORM\OneToOne(mappedBy: 'votant', cascade: ['persist', 'remove'])]
    private ?Votes $vote = null;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->prenoms . ' ' . $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityLibelle(): ?string
    {
        return $this->prenoms . ' ' . $this->nom;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(string $prenoms): static
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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

    public function getFixe(): ?string
    {
        return $this->fixe;
    }

    public function setFixe(?string $fixe): static
    {
        $this->fixe = $fixe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(?string $commune): static
    {
        $this->commune = $commune;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getService(): ?Parametres
    {
        return $this->service;
    }

    public function setService(?Parametres $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getPhoto(): ?Files
    {
        return $this->photo;
    }

    public function setPhoto(?Files $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAnniversaire(): ?\DateTime
    {
        return $this->anniversaire;
    }

    public function setAnniversaire(?\DateTime $anniversaire): static
    {
        $this->anniversaire = $anniversaire;

        return $this;
    }

    /**
     * @return Collection<int, Votes>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Votes $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setAgent($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getAgent() === $this) {
                $vote->setAgent(null);
            }
        }

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

    public function getVote(): ?Votes
    {
        return $this->vote;
    }

    public function setVote(Votes $vote): static
    {
        // set the owning side of the relation if necessary
        if ($vote->getVotant() !== $this) {
            $vote->setVotant($this);
        }

        $this->vote = $vote;

        return $this;
    }
}
