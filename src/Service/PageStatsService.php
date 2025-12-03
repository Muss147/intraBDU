<?php

namespace App\Service;

use Symfony\Component\Routing\RouterInterface;

class PageStatsService
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getTotalPages(): int
    {
        $routes = $this->router->getRouteCollection();
        $count = 0;

        foreach ($routes as $route) {
            $path = $route->getPath();

            // Ignore les routes qui ne sont pas accessibles à l'utilisateur
            if (str_starts_with($path, '/_') || str_contains($path, '{')) {
                continue; // ignore les routes système et dynamiques
            }

            $count++;
        }

        return $count;
    }
}