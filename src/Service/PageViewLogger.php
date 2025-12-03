<?php

// src/Service/PageViewLogger.php
namespace App\Service;

use App\Entity\PageView;
use Doctrine\ORM\EntityManagerInterface;

class PageViewLogger
{
    private EntityManagerInterface $em;
    private array $queue = [];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function log(string $routeName, string $sessionId, ?string $ip = null, ?string $userAgent = null): void
    {
        // Vérifie dans la queue locale
        foreach ($this->queue as $pv) {
            if ($pv->getRouteName() === $routeName && $pv->getSessionId() === $sessionId) {
                return; // déjà présent → on ignore
            }
        }

        // Vérifie en base
        $exists = $this->em->getRepository(PageView::class)->findOneBy([
            'routeName' => $routeName,
            'sessionId' => $sessionId
        ]);

        if ($exists) return;

        // Ajoute dans la queue
        $this->queue[] = new PageView($routeName, $sessionId, $ip, $userAgent);

        if (count($this->queue) >= 20) {
            $this->flushQueue();
        }
    }

    public function flushQueue(): void
    {
        foreach ($this->queue as $pageView) {
            $this->em->persist($pageView);
        }

        $this->em->flush();
        $this->queue = [];
    }
}