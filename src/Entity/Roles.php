<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RolesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Users>
     */
    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'role')]
    private Collection $users;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Autorisations>
     */
    #[ORM\OneToMany(targetEntity: Autorisations::class, mappedBy: 'role', cascade: ['persist'], orphanRemoval: true)]
    private Collection $autorisations;

    #[ORM\Column(nullable: true)]
    private ?bool $allAccess = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->autorisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityLibelle(): ?string
    {
        return $this->libelle;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): static
    {
        if ($this->libelle) {
            $slugger = new AsciiSlugger('fr'); // Support du français
            $slug = $slugger->slug($this->libelle)->lower();
            $this->slug = $slug;
        }
        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

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

    public function __toString()
    {
        return $this->libelle;   
    }

    /**
     * @return Collection<int, Autorisations>
     */
    public function getAutorisations(): Collection
    {
        return $this->autorisations;
    }

    public function addAutorisation(Autorisations $autorisation): static
    {
        if (!$this->autorisations->contains($autorisation)) {
            $this->autorisations->add($autorisation);
            $autorisation->setRole($this);
        }

        return $this;
    }

    public function removeAutorisation(Autorisations $autorisation): static
    {
        if ($this->autorisations->removeElement($autorisation)) {
            // set the owning side to null (unless already changed)
            if ($autorisation->getRole() === $this) {
                $autorisation->setRole(null);
            }
        }

        return $this;
    }

    public function isAllAccess(): ?bool
    {
        return $this->allAccess;
    }

    public function setAllAccess(?bool $allAccess): static
    {
        $this->allAccess = $allAccess;

        return $this;
    }
}
