<?php

namespace App\Entity;

use App\Repository\IncidentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncidentsRepository::class)]
class Incidents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $etape = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateRemonte = null;

    #[ORM\ManyToOne(inversedBy: 'incidentsByAgence')]
    private ?Parametres $agence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\ManyToOne(inversedBy: 'incidents')]
    private ?Parametres $direction = null;

    #[ORM\Column(length: 255)]
    private ?string $rapporteur = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $montantEstime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $datePerte = null;

    #[ORM\ManyToOne(inversedBy: 'incidentsByDirection')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parametres $directionImpacte = null;

    #[ORM\Column(nullable: true)]
    private ?int $montantObtenu = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateRecup = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateCompta = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $natureRecup = null;

    #[ORM\Column(nullable: true)]
    private ?int $montantNet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $consequences = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $solutions = [];

    #[ORM\ManyToOne(inversedBy: 'incidentsByCateg')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parametres $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'incidentsBySousCateg')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parametres $sousCategorie = null;

    #[ORM\ManyToOne(inversedBy: 'incidentsByProcessus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parametres $processus = null;

    #[ORM\ManyToOne(inversedBy: 'incidentsBySousProcessus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parametres $sousProcessus = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTime $createdAt = null;

    /**
     * @var Collection<int, Files>
     */
    #[ORM\OneToMany(targetEntity: Files::class, mappedBy: 'incidents')]
    private Collection $pieceJointe;

    #[ORM\Column(nullable: true)]
    private ?bool $corrigee = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $corrections = null;

    public function __construct()
    {
        $this->pieceJointe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtape(): ?string
    {
        return $this->etape;
    }

    public function setEtape(string $etape): static
    {
        $this->etape = $etape;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateRemonte(): ?\DateTime
    {
        return $this->dateRemonte;
    }

    public function setDateRemonte(\DateTime $dateRemonte): static
    {
        $this->dateRemonte = $dateRemonte;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

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

    public function getRapporteur(): ?string
    {
        return $this->rapporteur;
    }

    public function setRapporteur(string $rapporteur): static
    {
        $this->rapporteur = $rapporteur;

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

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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

    public function getMontantEstime(): ?int
    {
        return $this->montantEstime;
    }

    public function setMontantEstime(?int $montantEstime): static
    {
        $this->montantEstime = $montantEstime;

        return $this;
    }

    public function getDatePerte(): ?\DateTime
    {
        return $this->datePerte;
    }

    public function setDatePerte(?\DateTime $datePerte): static
    {
        $this->datePerte = $datePerte;

        return $this;
    }

    public function getDirectionImpacte(): ?Parametres
    {
        return $this->directionImpacte;
    }

    public function setDirectionImpacte(?Parametres $directionImpacte): static
    {
        $this->directionImpacte = $directionImpacte;

        return $this;
    }

    public function getMontantObtenu(): ?int
    {
        return $this->montantObtenu;
    }

    public function setMontantObtenu(?int $montantObtenu): static
    {
        $this->montantObtenu = $montantObtenu;

        return $this;
    }

    public function getDateRecup(): ?\DateTime
    {
        return $this->dateRecup;
    }

    public function setDateRecup(?\DateTime $dateRecup): static
    {
        $this->dateRecup = $dateRecup;

        return $this;
    }

    public function getDateCompta(): ?\DateTime
    {
        return $this->dateCompta;
    }

    public function setDateCompta(?\DateTime $dateCompta): static
    {
        $this->dateCompta = $dateCompta;

        return $this;
    }

    public function getNatureRecup(): ?string
    {
        return $this->natureRecup;
    }

    public function setNatureRecup(?string $natureRecup): static
    {
        $this->natureRecup = $natureRecup;

        return $this;
    }

    public function getMontantNet(): ?int
    {
        return $this->montantNet;
    }

    public function setMontantNet(?int $montantNet): static
    {
        $this->montantNet = $montantNet;

        return $this;
    }

    public function getConsequences(): ?string
    {
        return $this->consequences;
    }

    public function setConsequences(?string $consequences): static
    {
        $this->consequences = $consequences;

        return $this;
    }

    public function getSolutions(): ?array
    {
        return $this->solutions;
    }

    public function setSolutions(?array $solutions): static
    {
        $this->solutions = $solutions;

        return $this;
    }

    public function getCategorie(): ?Parametres
    {
        return $this->categorie;
    }

    public function setCategorie(?Parametres $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getSousCategorie(): ?Parametres
    {
        return $this->sousCategorie;
    }

    public function setSousCategorie(?Parametres $sousCategorie): static
    {
        $this->sousCategorie = $sousCategorie;

        return $this;
    }

    public function getProcessus(): ?Parametres
    {
        return $this->processus;
    }

    public function setProcessus(?Parametres $processus): static
    {
        $this->processus = $processus;

        return $this;
    }

    public function getSousProcessus(): ?Parametres
    {
        return $this->sousProcessus;
    }

    public function setSousProcessus(?Parametres $sousProcessus): static
    {
        $this->sousProcessus = $sousProcessus;

        return $this;
    }

    public function getAgence(): ?Parametres
    {
        return $this->agence;
    }

    public function setAgence(?Parametres $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getPieceJointe(): Collection
    {
        return $this->pieceJointe;
    }

    public function addPieceJointe(Files $pieceJointe): static
    {
        if (!$this->pieceJointe->contains($pieceJointe)) {
            $this->pieceJointe->add($pieceJointe);
            $pieceJointe->setIncidents($this);
        }

        return $this;
    }

    public function removePieceJointe(Files $pieceJointe): static
    {
        if ($this->pieceJointe->removeElement($pieceJointe)) {
            // set the owning side to null (unless already changed)
            if ($pieceJointe->getIncidents() === $this) {
                $pieceJointe->setIncidents(null);
            }
        }

        return $this;
    }

    public function isCorrigee(): ?bool
    {
        return $this->corrigee;
    }

    public function setCorrigee(?bool $corrigee): static
    {
        $this->corrigee = $corrigee;

        return $this;
    }

    public function getCorrections(): ?string
    {
        return $this->corrections;
    }

    public function setCorrections(?string $corrections): static
    {
        $this->corrections = $corrections;

        return $this;
    }
}
