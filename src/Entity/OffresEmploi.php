<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffresEmploiRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('slug', message: 'Ce slug est déjà utilisé.')]
#[ORM\Entity(repositoryClass: OffresEmploiRepository::class)]
class OffresEmploi extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getEntityLibelle(): ?string
    {
        return $this->titre;
    }

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\ManyToOne(inversedBy: 'offresDirection')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parametres $direction = null;

    #[ORM\Column(length: 255)]
    private ?string $experience = null;

    #[ORM\Column(length: 255)]
    private ?string $niveauPoste = null;

    #[ORM\Column(length: 255)]
    private ?string $niveauFormation = null;

    #[ORM\ManyToOne(inversedBy: 'offresMetier')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parametres $metier = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateExpiration = null;

    #[ORM\Column]
    private ?int $postes = 1;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $missions = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $profils = [];

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $slug = null;

    /**
     * @var Collection<int, Postuler>
     */
    #[ORM\OneToMany(targetEntity: Postuler::class, mappedBy: 'offre')]
    private Collection $postulants;

    public function __construct()
    {
        $this->postulants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDirection(): ?Parametres
    {
        return $this->direction;
    }

    public function setDirection(?Parametres $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getNiveauPoste(): ?string
    {
        return $this->niveauPoste;
    }

    public function setNiveauPoste(string $niveauPoste): static
    {
        $this->niveauPoste = $niveauPoste;

        return $this;
    }

    public function getNiveauFormation(): ?string
    {
        return $this->niveauFormation;
    }

    public function setNiveauFormation(string $niveauFormation): static
    {
        $this->niveauFormation = $niveauFormation;

        return $this;
    }

    public function getMetier(): ?Parametres
    {
        return $this->metier;
    }

    public function setMetier(?Parametres $metier): static
    {
        $this->metier = $metier;

        return $this;
    }

    public function getDateExpiration(): ?\DateTime
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTime $dateExpiration): static
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getPostes(): ?int
    {
        return $this->postes;
    }

    public function setPostes(int $postes): static
    {
        $this->postes = $postes;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMissions(): array
    {
        return $this->missions;
    }

    public function setMissions(array $missions): self
    {
        $this->missions = $missions;

        return $this;
    }

    public function getProfils(): array
    {
        return $this->profils;
    }

    public function setProfils(array $profils): self
    {
        $this->profils = $profils;

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
        if ($this->titre) {
            $slugger = new AsciiSlugger('fr'); // Support du français
            $slug = $slugger->slug($this->titre)->lower();
            $this->slug = $slug;
        }
    }

    /**
     * @return Collection<int, Postuler>
     */
    public function getPostulants(): Collection
    {
        return $this->postulants;
    }

    public function addPostulant(Postuler $postulant): static
    {
        if (!$this->postulants->contains($postulant)) {
            $this->postulants->add($postulant);
            $postulant->setOffre($this);
        }

        return $this;
    }

    public function removePostulant(Postuler $postulant): static
    {
        if ($this->postulants->removeElement($postulant)) {
            // set the owning side to null (unless already changed)
            if ($postulant->getOffre() === $this) {
                $postulant->setOffre(null);
            }
        }

        return $this;
    }
}
