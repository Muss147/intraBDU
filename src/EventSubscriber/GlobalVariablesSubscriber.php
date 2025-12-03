<?php

namespace App\EventSubscriber;

use App\Repository\FilesRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class GlobalVariablesSubscriber implements EventSubscriberInterface
{
    private FilesRepository $filesRepository;
    private Environment $twig;

    public function __construct(FilesRepository $filesRepository, Environment $twig)
    {
        $this->filesRepository = $filesRepository;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Récupération des deux fichiers (desktop & mobile)
        $desktop = $this->filesRepository->findOneBy(['type' => 'theme cover desktop']);
        $mobile  = $this->filesRepository->findOneBy(['type' => 'theme cover mobile']);

        // On envoie un tableau global dans Twig
        $this->twig->addGlobal('themeCovers', [
            'desktop' => $desktop,
            'mobile'  => $mobile,
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}