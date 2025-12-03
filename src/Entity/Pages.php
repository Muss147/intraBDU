<?php

namespace App\Entity;

use App\Mapping\EntityBase;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\PagesRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: PagesRepository::class)]
class Pages extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Files $cover = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lien = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $emplacement = null;

    #[ORM\Column(nullable: true)]
    private ?int $rang = 0;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?bool $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $style = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $script = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCover(): ?Files
    {
        return $this->cover;
    }

    public function setCover(?Files $cover): static
    {
        $this->cover = $cover;

        return $this;
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

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): static
    {
        $this->lien = $lien;

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

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getRang(): ?int
    {
        return $this->rang;
    }

    public function setRang(int $rang): static
    {
        $this->rang = $rang;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        // $this->contenu = $contenu ? htmlspecialchars_decode($contenu) : null;
        // $this->contenu = $this->cleanQuillHtml($contenu);
        $this->contenu = $contenu;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(?string $style): static
    {
        // $this->style = $style ? htmlspecialchars_decode($style) : null;
        $this->style = $style;

        return $this;
    }

    public function getScript(): ?string
    {
        return $this->script;
    }

    public function setScript(?string $script): static
    {
        // $this->script = $script ? htmlspecialchars_decode($script) : null;
        $this->script = $script;

        return $this;
    }
    
    public function cleanQuillHtml(string $contenu): string
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true); // éviter warnings HTML mal formé
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $contenu);

        $xpath = new \DOMXPath($dom);

        // Trouver toutes les div.ql-code-block
        foreach ($xpath->query('//div[contains(@class,"ql-code-block")]') as $node) {
            while ($node->firstChild) {
                $node->parentNode->insertBefore($node->firstChild, $node);
            }
            $node->parentNode->removeChild($node);
        }

        // Récupérer seulement le contenu du body
        $body = $dom->getElementsByTagName('body')->item(0);
        return $dom->saveHTML($body);
    }
}
