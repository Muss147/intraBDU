<?php

namespace App\Mapping;

use DateTime;

/**
 * EntityBase Interface
 *
 * @author Lelle - Daniele Rostellato <lelle.daniele@gmail.com>
 */
interface EntityBaseInterface
{
    /**
     * Met à jour les horodatages (timestamps).
     *
     * @return void
     */
    public function updatedTimestamps(): void;

    /**
     * Définit la date de mise à jour.
     *
     * @param DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt): self;

    /**
     * Obtient la date de mise à jour.
     *
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime;

    /**
     * Définit la date de création.
     *
     * @param DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt): self;

    /**
     * Obtient la date de création.
     *
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime;
}
