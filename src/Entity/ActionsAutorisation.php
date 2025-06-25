<?php

namespace App\Entity;

use App\Repository\ActionsAutorisationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionsAutorisationRepository::class)]
class ActionsAutorisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $checked = null;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Autorisations $autorisation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Actions $action = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(?bool $checked): static
    {
        $this->checked = $checked;

        return $this;
    }

    public function getAutorisation(): ?Autorisations
    {
        return $this->autorisation;
    }

    public function setAutorisation(?Autorisations $autorisation): static
    {
        $this->autorisation = $autorisation;

        return $this;
    }

    public function getAction(): ?Actions
    {
        return $this->action;
    }

    public function setAction(?Actions $action): static
    {
        $this->action = $action;

        return $this;
    }
}
