<?php
// src/EventSubscriber/ActivityLoggerSubscriber.php

namespace App\EventSubscriber;

use App\Entity\ActivityLog;
use App\Service\IpLocationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;

class ActivityLoggerSubscriber implements EventSubscriberInterface
{
    private float $startTime = 0;

    public function __construct(
        private EntityManagerInterface $em,
        private RequestStack $requestStack,
        private Security $security,
        private IpLocationService $ipLocationService
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $this->startTime = microtime(true);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        $response = $event->getResponse();

        // Vérifie si un utilisateur est connecté
        $user = $this->security->getUser();
        if (!$user) {
            return; // Aucun utilisateur connecté, ne pas enregistrer
        }

        // On filtre uniquement certaines méthodes
        if (!in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            return;
        }

        $ip = $request->getClientIp();
        $device = $request->headers->get('User-Agent');
        $location = $this->ipLocationService->getLocation($ip);
        $code = $response->getStatusCode();

        $duration = round((microtime(true) - $this->startTime) * 1000, 2); // en ms
        $status = $code >= 500 ? 'ERR' : ($code >= 400 ? 'WRN' : 'OK');

        $log = new ActivityLog();
        $log->setType('modification');
        $log->setMethod($method);
        $log->setPath($request->getPathInfo());
        $log->setIpAddress($ip);
        $log->setDevice($device);
        $log->setLocation($location);
        $log->setStatus($status);
        $log->setResponseCode($code);
        $log->setDuration($duration);
        $log->setCreatedAt(new \DateTime());
        $log->setUser($user);

        $this->em->persist($log);
        $this->em->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 0],
            KernelEvents::RESPONSE => ['onKernelResponse', -10],
        ];
    }
}