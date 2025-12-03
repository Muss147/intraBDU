<?php // src/Twig/CustomTwigExtension.php

namespace App\Twig;

use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use IntlDateFormatter;
use Twig\TwigFilter;
use Twig\TwigFunction;

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

    public function getFunctions()
    {
        return [
            new TwigFunction('is_html', [$this, 'isHtml']),
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

    public function formatDateFr(DateTimeInterface $date, string $format = 'EEEE d MMMM Y'): string
    {
        // Vérifie si l'extension intl est installée
        if (class_exists(\IntlDateFormatter::class)) {
            $formatter = new \IntlDateFormatter(
                'fr_FR',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                $date->getTimezone(),
                \IntlDateFormatter::GREGORIAN,
                $format
            );

            return $formatter->format($date);
        }

        // Fallback manuel si intl n’est pas dispo
        $mois = [
            1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
            5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
            9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
        ];

        $jours = [
            'Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi',
            'Thursday' => 'jeudi', 'Friday' => 'vendredi',
            'Saturday' => 'samedi', 'Sunday' => 'dimanche'
        ];

        return strtr($date->format($format), array_merge($jours, $mois));
    }
    
    public function isHtml(?string $text): bool
    {
        if (empty($text)) return false;
        return $text !== strip_tags($text);
    }
}