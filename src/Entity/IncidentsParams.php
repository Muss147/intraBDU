<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\IncidentsParamsRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: IncidentsParamsRepository::class)]
class IncidentsParams extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, Incidents>
     */
    #[ORM\OneToMany(targetEntity: Incidents::class, mappedBy: 'categorie')]
    private Collection $incidentsByCateg;

    /**
     * @var Collection<int, Incidents>
     */
    #[ORM\OneToMany(targetEntity: Incidents::class, mappedBy: 'sousCategorie')]
    private Collection $incidentsBySousCateg;

    /**
     * @var Collection<int, Incidents>
     */
    #[ORM\OneToMany(targetEntity: Incidents::class, mappedBy: 'processus')]
    private Collection $incidentsByProcessus;

    /**
     * @var Collection<int, Incidents>
     */
    #[ORM\OneToMany(targetEntity: Incidents::class, mappedBy: 'sousProcessus')]
    private Collection $incidentsBySousProcessus;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    public function __construct()
    {
        $this->incidentsByCateg = new ArrayCollection();
        $this->incidentsBySousCateg = new ArrayCollection();
        $this->incidentsByProcessus = new ArrayCollection();
        $this->incidentsBySousProcessus = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Incidents>
     */
    public function getIncidentsByCateg(): Collection
    {
        return $this->incidentsByCateg;
    }

    public function addIncidentsByCateg(Incidents $incidentsByCateg): static
    {
        if (!$this->incidentsByCateg->contains($incidentsByCateg)) {
            $this->incidentsByCateg->add($incidentsByCateg);
            $incidentsByCateg->setCategorie($this);
        }

        return $this;
    }

    public function removeIncidentsByCateg(Incidents $incidentsByCateg): static
    {
        if ($this->incidentsByCateg->removeElement($incidentsByCateg)) {
            // set the owning side to null (unless already changed)
            if ($incidentsByCateg->getCategorie() === $this) {
                $incidentsByCateg->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Incidents>
     */
    public function getIncidentsBySousCateg(): Collection
    {
        return $this->incidentsBySousCateg;
    }

    public function addIncidentsBySousCateg(Incidents $incidentsBySousCateg): static
    {
        if (!$this->incidentsBySousCateg->contains($incidentsBySousCateg)) {
            $this->incidentsBySousCateg->add($incidentsBySousCateg);
            $incidentsBySousCateg->setSousCategorie($this);
        }

        return $this;
    }

    public function removeIncidentsBySousCateg(Incidents $incidentsBySousCateg): static
    {
        if ($this->incidentsBySousCateg->removeElement($incidentsBySousCateg)) {
            // set the owning side to null (unless already changed)
            if ($incidentsBySousCateg->getSousCategorie() === $this) {
                $incidentsBySousCateg->setSousCategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Incidents>
     */
    public function getIncidentsByProcessus(): Collection
    {
        return $this->incidentsByProcessus;
    }

    public function addIncidentsByProcessu(Incidents $incidentsByProcessu): static
    {
        if (!$this->incidentsByProcessus->contains($incidentsByProcessu)) {
            $this->incidentsByProcessus->add($incidentsByProcessu);
            $incidentsByProcessu->setProcessus($this);
        }

        return $this;
    }

    public function removeIncidentsByProcessu(Incidents $incidentsByProcessu): static
    {
        if ($this->incidentsByProcessus->removeElement($incidentsByProcessu)) {
            // set the owning side to null (unless already changed)
            if ($incidentsByProcessu->getProcessus() === $this) {
                $incidentsByProcessu->setProcessus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Incidents>
     */
    public function getIncidentsBySousProcessus(): Collection
    {
        return $this->incidentsBySousProcessus;
    }

    public function addIncidentsBySousProcessu(Incidents $incidentsBySousProcessu): static
    {
        if (!$this->incidentsBySousProcessus->contains($incidentsBySousProcessu)) {
            $this->incidentsBySousProcessus->add($incidentsBySousProcessu);
            $incidentsBySousProcessu->setSousProcessus($this);
        }

        return $this;
    }

    public function removeIncidentsBySousProcessu(Incidents $incidentsBySousProcessu): static
    {
        if ($this->incidentsBySousProcessus->removeElement($incidentsBySousProcessu)) {
            // set the owning side to null (unless already changed)
            if ($incidentsBySousProcessu->getSousProcessus() === $this) {
                $incidentsBySousProcessu->setSousProcessus(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, IncidentsParams>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(IncidentsParams $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(IncidentsParams $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
}
