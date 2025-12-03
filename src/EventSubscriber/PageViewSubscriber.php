<?php

// src/EventSubscriber/PageViewSubscriber.php
namespace App\EventSubscriber;

use App\Service\PageViewLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PageViewSubscriber implements EventSubscriberInterface
{
    private PageViewLogger $logger;
    private RequestStack $requestStack;

    public function __construct(PageViewLogger $logger, RequestStack $requestStack)
    {
        $this->logger = $logger;
        $this->requestStack = $requestStack;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) return;

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');
        if (!$route || $request->isXmlHttpRequest()) return;

        $session = $this->requestStack->getSession();
        $sessionId = $session ? $session->getId() : null;
        if (!$sessionId) return;

        $ip = $request->getClientIp();
        $ua = $request->headers->get('User-Agent');

        $this->logger->log($route, $sessionId, $ip, $ua);
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onKernelRequest', 15]];
    }
}