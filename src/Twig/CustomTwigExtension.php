<?php // src/Twig/CustomTwigExtension.php

namespace App\Twig;

use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use IntlDateFormatter;
use Twig\TwigFilter;

class CustomTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('time_ago', [$this, 'timeAgo']),
            new TwigFilter('sum', [$this, 'arraySum']),
            new TwigFilter('date_fr', [$this, 'formatDateFr']),
        ];
    }

    public function timeAgo(\DateTimeInterface $dateTime)
    {
        $now = new \DateTime();
        $diff = $now->diff($dateTime);

        if ($diff->y > 0) {
            return $dateTime->format('d/m/Y');
        }
        if ($diff->m > 0) {
            return $diff->m === 1 ? 'Il y a un mois' : 'Il y a ' . $diff->m . ' mois';
        }
        if ($diff->d > 7) {
            return 'Il y a ' . round($diff->d / 7) . ' semaines';
        }
        if ($diff->d === 1) {
            return 'Hier';
        }
        if ($diff->d > 1) {
            return 'Il y a ' . $diff->d . ' jours';
        }
        if ($diff->h > 0) {
            return 'Il y a ' . $diff->h . ' heures';
        }
        if ($diff->i > 0) {
            return 'Il y a ' . $diff->i . ' minutes';
        }

        return 'Il y a quelques instants';
    }

    public function arraySum(array $items): float
    {
        return array_sum($items);
        // return array_sum(array_map(function ($item) use ($property) {
        //     return $item[$property] ?? $item->$property ?? 0;
        // }, $items));
    }

    public function formatDateFr(DateTimeInterface $date, string $format = 'l, d M Y'): string
    {
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        return strftime($format, $date->getTimestamp());
    }
}