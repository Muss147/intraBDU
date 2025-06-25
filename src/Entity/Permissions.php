<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use App\Repository\PermissionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionsRepository::class)]
class Permissions extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $core = null;

    /**
     * @var Collection<int, Autorisations>
     */
    #[ORM\OneToMany(targetEntity: Autorisations::class, mappedBy: 'permission', cascade: ['persist'], orphanRemoval: true)]
    private Collection $autorisations;

    /**
     * @var Collection<int, Actions>
     */
    #[ORM\OneToMany(targetEntity: Actions::class, mappedBy: 'permission', orphanRemoval: true, cascade: ['persist'])]
    private Collection $actions;

    public function __construct()
    {
        $this->autorisations = new ArrayCollection();
        $this->actions = new ArrayCollection();
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
    public function generateSlug(): void
    {
        if ($this->libelle) {
            $slugger = new AsciiSlugger('fr'); // Support du français
            $slug = $slugger->slug($this->libelle)->lower();
            $this->slug = $slug;
        }
    }

    public function isCore(): ?bool
    {
        return $this->core;
    }

    public function setCore(?bool $core): static
    {
        $this->core = $core;

        return $this;
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
            $autorisation->setPermission($this);
        }

        return $this;
    }

    public function removeAutorisation(Autorisations $autorisation): static
    {
        if ($this->autorisations->removeElement($autorisation)) {
            // set the owning side to null (unless already changed)
            if ($autorisation->getPermission() === $this) {
                $autorisation->setPermission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Actions>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Actions $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->setPermission($this);
        }

        return $this;
    }

    public function removeAction(Actions $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getPermission() === $this) {
                $action->setPermission(null);
            }
        }

        return $this;
    }
}
