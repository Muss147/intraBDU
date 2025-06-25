<?php

namespace App\Mapping;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Class EntityBase
 *
 * PHP version 7.1
 *
 * LICENSE: MIT
 *
 * @package    App\Mapping
 * @author     Lelle - Daniele Rostellato <lelle.daniele@gmail.com>
 * @license    MIT
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */
#[ORM\HasLifecycleCallbacks]
class EntityBase implements EntityBaseInterface
{   
    #[ORM\Column(name: "created_at", type: "datetime", nullable: true)]
    protected ?DateTime $createdAt = null;

    #[ORM\Column(name: "updated_at", type: "datetime", nullable: true)]
    protected ?DateTime $updatedAt = null;

    // DO NOT use ORM annotations to map this property. See bundle configuration section for more info
    #[ORM\Column(name: "deleted_at", type: "datetime", nullable: true)] 
    protected ?\DateTime $deletedAt = null; 
    

    #[ORM\ManyToOne(targetEntity: \App\Entity\Users::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    protected $createdUser;

    #[ORM\ManyToOne(targetEntity: \App\Entity\Users::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    protected $updatedUser;

    #[ORM\ManyToOne(targetEntity: \App\Entity\Users::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "CASCADE")]
    protected $deletedUser;


    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getDeletedAt() : ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTime $deletedAt = null) : void
    {
        $this->deletedAt = $deletedAt;
    }

    public function setCreatedUser($createdUser): self
    {
        $this->createdUser = $createdUser;
        return $this;
    }

    public function getCreatedUser()
    {
        return $this->createdUser;
    }

    public function setUpdatedUser($updatedUser = null): self
    {
        $this->updatedUser = $updatedUser;
        return $this;
    }

    public function getUpdatedUser()
    {
        return $this->updatedUser;
    }

    public function setDeletedUser($deletedUser = null): self
    {
        $this->deletedUser = $deletedUser;
        return $this;
    }

    public function getDeletedUser()
    {
        return $this->deletedUser;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new DateTime('now');
        $this->setUpdatedAt($dateTimeNow);

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedUserstamps($currentUser): void
    {
        $this->setUpdatedUser($currentUser);

        if ($this->getCreatedUser() === null) {
            $this->setCreatedUser($currentUser);
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function remove($currentUser): void
    {
        // $currentUser = $this->security->getUser();
        $dateTimeNow = new DateTime('now');

        $this->setDeletedUser($currentUser);
        $this->setDeletedAt($dateTimeNow);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function restore(): void
    {
        $this->setDeletedUser(null);
        $this->setDeletedAt(null);
    }

}
