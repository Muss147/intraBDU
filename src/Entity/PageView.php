<?php
namespace App\Entity;

use App\Repository\PageViewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

# src/Entity/PageView.php
#[ORM\Entity(repositoryClass: PageViewRepository::class)]
class PageView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $routeName;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $visitedAt;

    #[ORM\Column(length: 64)]
    private string $sessionId;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    public function __construct(string $routeName, string $sessionId, ?string $ip = null, ?string $userAgent = null)
    {
        $this->routeName = $routeName;
        $this->sessionId = $sessionId;
        $this->visitedAt = new \DateTime();
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    // getters & setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): static
    {
        $this->routeName = $routeName;

        return $this;
    }

    public function getVisitedAt(): ?\DateTimeInterface
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeInterface $visitedAt): static
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}