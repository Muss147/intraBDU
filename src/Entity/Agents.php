<?php

namespace App\Entity;

use App\Entity\Files;
use App\Entity\Votes;
use App\Entity\Parametres;
use App\Mapping\EntityBase;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\AgentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('matricule', message: 'Ce matricule est déjà utilisé.')]
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

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\ManyToOne(inversedBy: 'agentsByBureau')]
    private ?Parametres $bureau = null;

    #[ORM\ManyToOne(inversedBy: 'agents')]
    private ?Parametres $service = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Files $photo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $anniversaire = null;

    #[ORM\OneToMany(mappedBy: 'agent', targetEntity: Votes::class)]
    private Collection $votesRecus;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $matricule = null;

    #[ORM\OneToMany(mappedBy: 'votant', targetEntity: Votes::class, cascade: ['persist', 'remove'])]
    private Collection $votesEffectues;

    public function __construct()
    {
        $this->votesRecus = new ArrayCollection();
        $this->votesEffectues = new ArrayCollection();
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

    public function getPoints(): int
    {
        $total = 0;
        $now = new \DateTime();
        $mois = (int) $now->format('m');
        $annee = (int) $now->format('Y');

        foreach ($this->votesRecus as $vote) {
            $dateVote = $vote->getVotedAt();
            if ((int) $dateVote->format('m') === $mois && (int) $dateVote->format('Y') === $annee) {
                foreach ($vote->getNotes() as $note) {
                    $total += $note->getNote();
                }
            }
        }

        return $total;
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

    public function getBureau(): ?Parametres
    {
        return $this->bureau;
    }

    public function setBureau(?Parametres $bureau): static
    {
        $this->bureau = $bureau;

        return $this;
    }
    
    public function getHistoriqueMensuel(EntityManagerInterface $em): array
    {
        $voteRepo = $em->getRepository(Votes::class);
        $agentId = $this->getId();

        // Regrouper tous les votes par mois pour cet agent
        $votes = $voteRepo->createQueryBuilder('v')
            ->leftJoin('v.notes', 'n')
            ->addSelect('n')
            ->where('v.votant = :agent')
            ->setParameter('agent', $this)
            ->getQuery()
            ->getResult();

        $grouped = [];

        foreach ($votes as $vote) {
            $month = $vote->getVotedAt()->format('Y-m'); // ex : "2025-07"
            if (!isset($grouped[$month])) {
                $grouped[$month] = [
                    'points' => 0,
                    'count' => 0,
                    'date' => \DateTime::createFromFormat('Y-m', $month),
                ];
            }

            foreach ($vote->getNotes() as $note) {
                $grouped[$month]['points'] += $note->getNote();
                $grouped[$month]['count']++;
            }
        }

        // Ajouter la moyenne et le rang
        foreach ($grouped as $month => &$data) {
            $data['moyenne'] = $data['count'] > 0 ? round($data['points'] / $data['count'], 1) : 0;

            // Calcul du rang pour ce mois
            $data['rang'] = $this->getRangForMonth($em, $data['date']);
        }

        return $grouped;
    }

    public function getRangForMonth(EntityManagerInterface $em, \DateTime $month): int
    {
        $start = (clone $month)->modify('first day of this month')->setTime(0, 0);
        $end = (clone $month)->modify('last day of this month')->setTime(23, 59, 59);

        $agentRepo = $em->getRepository(Agents::class);
        $agents = $agentRepo->findAll();

        $classement = [];

        foreach ($agents as $a) {
            $points = 0;
            foreach ($a->getVotesRecus() as $vote) {
                if ($vote->getVotedAt() >= $start && $vote->getVotedAt() <= $end) {
                    foreach ($vote->getNotes() as $note) {
                        $points += $note->getNote();
                    }
                }
            }
            $classement[] = [
                'agent' => $a,
                'points' => $points
            ];
        }

        usort($classement, fn($a, $b) => $b['points'] <=> $a['points']);

        foreach ($classement as $index => $entry) {
            if ($entry['agent']->getId() === $this->getId()) {
                return $index + 1;
            }
        }

        return count($classement);
    }

    /**
     * @return Collection<int, Votes>
     */
    public function getVotesRecus(): Collection
    {
        return $this->votesRecus;
    }

    public function addVotesRecu(Votes $votesRecu): static
    {
        if (!$this->votesRecus->contains($votesRecu)) {
            $this->votesRecus->add($votesRecu);
            $votesRecu->setAgent($this);
        }

        return $this;
    }

    public function removeVotesRecu(Votes $votesRecu): static
    {
        if ($this->votesRecus->removeElement($votesRecu)) {
            // set the owning side to null (unless already changed)
            if ($votesRecu->getAgent() === $this) {
                $votesRecu->setAgent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Votes>
     */
    public function getVotesEffectues(): Collection
    {
        return $this->votesEffectues;
    }

    public function addVotesEffectue(Votes $votesEffectue): static
    {
        if (!$this->votesEffectues->contains($votesEffectue)) {
            $this->votesEffectues->add($votesEffectue);
            $votesEffectue->setVotant($this);
        }

        return $this;
    }

    public function removeVotesEffectue(Votes $votesEffectue): static
    {
        if ($this->votesEffectues->removeElement($votesEffectue)) {
            // set the owning side to null (unless already changed)
            if ($votesEffectue->getVotant() === $this) {
                $votesEffectue->setVotant(null);
            }
        }

        return $this;
    }
}
