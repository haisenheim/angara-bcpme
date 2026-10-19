<?php

namespace App\Support;

/**
 * Comptage de mots d'un contenu HTML (éditeur riche). Même règle que resources/js/inertia/utils/words.js :
 * suites de lettres / chiffres, apostrophes et traits d'union internes inclus.
 */
final class WordCount
{
    public static function of(?string $html): int
    {
        if ($html === null || $html === '') {
            return 0;
        }
        $text = html_entity_decode(strip_tags(preg_replace('#<(br|/p|/div|/li|/h[1-6])[^>]*>#i', ' $0', $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return preg_match_all("/[\\p{L}\\p{N}]+(?:['’-][\\p{L}\\p{N}]+)*/u", $text);
    }

    public static function format(int $n): string
    {
        return number_format($n, 0, ',', ' ').' mot'.($n > 1 ? 's' : '');
    }
}
