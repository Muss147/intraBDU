<?php

// src/EventSubscriber/PageViewFlushSubscriber.php
namespace App\EventSubscriber;

use App\Service\PageViewLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PageViewFlushSubscriber implements EventSubscriberInterface
{
    private PageViewLogger $logger;

    public function __construct(PageViewLogger $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $this->logger->flushQueue();
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::TERMINATE => 'onKernelTerminate'];
    }
}