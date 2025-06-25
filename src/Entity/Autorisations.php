<?php

namespace App\Entity;

use App\Repository\AutorisationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutorisationsRepository::class)]
class Autorisations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'autorisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Permissions $permission = null;

    #[ORM\ManyToOne(inversedBy: 'autorisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Roles $role = null;

    /**
     * @var Collection<int, ActionsAutorisation>
     */
    #[ORM\OneToMany(targetEntity: ActionsAutorisation::class, mappedBy: 'autorisation', cascade: ['persist'], orphanRemoval: true)]
    private Collection $actions;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPermission(): ?Permissions
    {
        return $this->permission;
    }

    public function setPermission(?Permissions $permission): static
    {
        $this->permission = $permission;

        return $this;
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, ActionsAutorisation>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(ActionsAutorisation $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->setAutorisation($this);
        }

        return $this;
    }

    public function removeAction(ActionsAutorisation $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getAutorisation() === $this) {
                $action->setAutorisation(null);
            }
        }

        return $this;
    }
}
