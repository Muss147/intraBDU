<?php // src/Twig/EmailAssetExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Asset\Packages;

class EmailAssetExtension extends AbstractExtension
{
    private UrlGeneratorInterface $urlGenerator;
    private Packages $packages;

    public function __construct(UrlGeneratorInterface $urlGenerator, Packages $packages)
    {
        $this->urlGenerator = $urlGenerator;
        $this->packages = $packages;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('email_asset', [$this, 'getEmailAsset']),
        ];
    }

    public function getEmailAsset(string $path): string
    {
        return $this->urlGenerator->generate(
            'dashboard', [], // ou n'importe quelle route valide
            UrlGeneratorInterface::ABSOLUTE_URL
        ) . ltrim($this->packages->getUrl($path), '/');
    }
}