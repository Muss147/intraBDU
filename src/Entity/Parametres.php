<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParametresRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: ParametresRepository::class)]
class Parametres extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    /**
     * @var Collection<int, Agents>
     */
    #[ORM\OneToMany(targetEntity: Agents::class, mappedBy: 'service')]
    private Collection $agents;

    /**
     * @var Collection<int, OffresEmploi>
     */
    #[ORM\OneToMany(targetEntity: OffresEmploi::class, mappedBy: 'direction')]
    private Collection $offresDirection;

    /**
     * @var Collection<int, OffresEmploi>
     */
    #[ORM\OneToMany(targetEntity: OffresEmploi::class, mappedBy: 'metier')]
    private Collection $offresMetier;

    /**
     * @var Collection<int, Incidents>
     */
    #[ORM\OneToMany(targetEntity: Incidents::class, mappedBy: 'direction')]
    private Collection $incidents;

    /**
     * @var Collection<int, Incidents>
     */
    #[ORM\OneToMany(targetEntity: Incidents::class, mappedBy: 'directionImpacte')]
    private Collection $incidentsByDirection;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->agents = new ArrayCollection();
        $this->offresDirection = new ArrayCollection();
        $this->offresMetier = new ArrayCollection();
        $this->incidents = new ArrayCollection();
        $this->incidentsByDirection = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
     * @return Collection<int, Parametres>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Parametres $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Parametres $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Agents>
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgents(Agents $agents): static
    {
        if (!$this->agents->contains($agents)) {
            $this->agents->add($agents);
            $agents->setService($this);
        }

        return $this;
    }

    public function removeAgents(Agents $agents): static
    {
        if ($this->agents->removeElement($agents)) {
            // set the owning side to null (unless already changed)
            if ($agents->getService() === $this) {
                $agents->setService(null);
            }
        }

        return $this;
    }

    public function addAgent(Agents $agent): static
    {
        if (!$this->agents->contains($agent)) {
            $this->agents->add($agent);
            $agent->setService($this);
        }

        return $this;
    }

    public function removeAgent(Agents $agent): static
    {
        if ($this->agents->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getService() === $this) {
                $agent->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OffresEmploi>
     */
    public function getOffresDirection(): Collection
    {
        return $this->offresDirection;
    }

    public function addOffreDirection(OffresEmploi $offresEmploi): static
    {
        if (!$this->offresDirection->contains($offresEmploi)) {
            $this->offresDirection->add($offresEmploi);
            $offresEmploi->setDirection($this);
        }

        return $this;
    }

    public function removeOffreDirection(OffresEmploi $offresEmploi): static
    {
        if ($this->offresDirection->removeElement($offresEmploi)) {
            // set the owning side to null (unless already changed)
            if ($offresEmploi->getDirection() === $this) {
                $offresEmploi->setDirection(null);
            }
        }

        return $this;
    }

    public function addOffresDirection(OffresEmploi $offresDirection): static
    {
        if (!$this->offresDirection->contains($offresDirection)) {
            $this->offresDirection->add($offresDirection);
            $offresDirection->setDirection($this);
        }

        return $this;
    }

    public function removeOffresDirection(OffresEmploi $offresDirection): static
    {
        if ($this->offresDirection->removeElement($offresDirection)) {
            // set the owning side to null (unless already changed)
            if ($offresDirection->getDirection() === $this) {
                $offresDirection->setDirection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OffresEmploi>
     */
    public function getOffresMetier(): Collection
    {
        return $this->offresMetier;
    }

    public function addOffresMetier(OffresEmploi $offresMetier): static
    {
        if (!$this->offresMetier->contains($offresMetier)) {
            $this->offresMetier->add($offresMetier);
            $offresMetier->setMetier($this);
        }

        return $this;
    }

    public function removeOffresMetier(OffresEmploi $offresMetier): static
    {
        if ($this->offresMetier->removeElement($offresMetier)) {
            // set the owning side to null (unless already changed)
            if ($offresMetier->getMetier() === $this) {
                $offresMetier->setMetier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Incidents>
     */
    public function getIncidents(): Collection
    {
        return $this->incidents;
    }

    public function addIncident(Incidents $incident): static
    {
        if (!$this->incidents->contains($incident)) {
            $this->incidents->add($incident);
            $incident->setDirection($this);
        }

        return $this;
    }

    public function removeIncident(Incidents $incident): static
    {
        if ($this->incidents->removeElement($incident)) {
            // set the owning side to null (unless already changed)
            if ($incident->getDirection() === $this) {
                $incident->setDirection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Incidents>
     */
    public function getIncidentsByDirection(): Collection
    {
        return $this->incidentsByDirection;
    }

    public function addIncidentsByDirection(Incidents $incidentsByDirection): static
    {
        if (!$this->incidentsByDirection->contains($incidentsByDirection)) {
            $this->incidentsByDirection->add($incidentsByDirection);
            $incidentsByDirection->setDirectionImpacte($this);
        }

        return $this;
    }

    public function removeIncidentsByDirection(Incidents $incidentsByDirection): static
    {
        if ($this->incidentsByDirection->removeElement($incidentsByDirection)) {
            // set the owning side to null (unless already changed)
            if ($incidentsByDirection->getDirectionImpacte() === $this) {
                $incidentsByDirection->setDirectionImpacte(null);
            }
        }

        return $this;
    }
}
