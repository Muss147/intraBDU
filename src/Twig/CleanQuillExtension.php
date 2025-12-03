<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CleanQuillExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('clean_quill', [$this, 'cleanQuill'], ['is_safe' => ['html']]),
        ];
    }

    public function cleanQuill(?string $contenu): string
    {
        if (empty($contenu)) {
            return '';
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');

        // Encapsuler dans un wrapper pour travailler uniquement sur le fragment
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="wrapper">' . $contenu . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
        $wrapper = $dom->getElementById('wrapper');

        // --- 1) Nettoyage des containers Quill ---
        foreach (iterator_to_array($xpath->query('//div[contains(@class,"ql-code-block-container")]')) as $container) {
            while ($container->firstChild) {
                $container->parentNode->insertBefore($container->firstChild, $container);
            }
            $container->parentNode->removeChild($container);
        }

        // --- 2) Fusion des blocs ql-code-block ---
        $blocks = iterator_to_array($xpath->query('//div[contains(@class,"ql-code-block")]'));
        foreach ($blocks as $block) {
            $prev = $block->previousSibling;
            while ($prev && $prev->nodeType !== XML_ELEMENT_NODE) {
                $prev = $prev->previousSibling;
            }

            if ($prev && $prev->nodeName === 'div' && $prev->attributes->length > 0) {
                while ($block->firstChild) {
                    $prev->appendChild($block->firstChild);
                }
                $block->parentNode->removeChild($block);
                continue;
            }

            while ($block->firstChild) {
                $block->parentNode->insertBefore($block->firstChild, $block);
            }
            $block->parentNode->removeChild($block);
        }

        // --- 3) Suppression des <br> inutiles ---
        foreach (iterator_to_array($xpath->query('//br')) as $br) {
            $br->parentNode->removeChild($br);
        }

        // --- 4) Ré-attachement automatique pour toutes les balises orphelines ---
        $validParents = [
            'li' => ['ul', 'ol', 'menu'],
            'tr' => ['table', 'thead', 'tbody', 'tfoot'],
            'td' => ['tr'],
            'th' => ['tr'],
            'option' => ['select', 'datalist', 'optgroup'],
            'thead' => ['table'],
            'tbody' => ['table'],
            'tfoot' => ['table'],
            'col' => ['colgroup', 'table'],
            'colgroup' => ['table'],
            'figcaption' => ['figure'],
            'caption' => ['table'],
            'p' => ['div', 'section', 'article', 'body'],
        ];

        // Fonction pour rattacher un élément orphelin
        $attachOrphan = function(\DOMElement $element) use ($dom, $validParents, $wrapper) {
            $tag = strtolower($element->nodeName);
            if (!isset($validParents[$tag])) return;

            $parents = $validParents[$tag];
            $found = null;

            // 1) Chercher parent valide parmi les frères précédents
            $walker = $element->previousSibling;
            while ($walker) {
                if ($walker->nodeType === XML_ELEMENT_NODE && in_array(strtolower($walker->nodeName), $parents)) {
                    $found = $walker;
                    break;
                }
                $walker = $walker->previousSibling;
            }

            // 2) Chercher parent valide parmi les ancêtres jusqu’au wrapper
            if (!$found) {
                $walker = $element->parentNode;
                while ($walker && $walker !== $wrapper) {
                    if ($walker->nodeType === XML_ELEMENT_NODE && in_array(strtolower($walker->nodeName), $parents)) {
                        $found = $walker;
                        break;
                    }
                    $walker = $walker->parentNode;
                }
            }

            // 3) Créer un parent valide si nécessaire
            if (!$found) {
                $found = $dom->createElement($parents[0]);
                $element->parentNode->insertBefore($found, $element);
            }

            // 4) Déplacer l’élément dans le parent
            $found->appendChild($element);
        };

        // Appliquer la fonction à tous les éléments orphelins connus
        foreach (array_keys($validParents) as $tag) {
            $orphans = iterator_to_array($xpath->query("//{$tag}[not(ancestor::" . implode(" or ancestor::", $validParents[$tag]) . ")]"));
            foreach ($orphans as $child) {
                $attachOrphan($child);
            }
        }

        // --- 5) Suppression des div/p vides ---
        foreach (iterator_to_array($xpath->query('//div[not(normalize-space())] | //p[not(normalize-space())]')) as $empty) {
            $empty->parentNode->removeChild($empty);
        }

        // --- 6) Récupérer le HTML final depuis #wrapper ---
        $output = '';
        foreach ($wrapper->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }

        // --- 7) Décoder entités HTML ---
        $output = html_entity_decode($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $output;
    }
}