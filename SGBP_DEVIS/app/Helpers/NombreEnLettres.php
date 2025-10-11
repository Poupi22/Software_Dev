<?php

namespace App\Helpers;

class NombreEnLettres
{
    private static array $unites = [
        '', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf',
        'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize',
        'dix-sept', 'dix-huit', 'dix-neuf',
    ];

    private static array $dizaines = [
        '', '', 'vingt', 'trente', 'quarante', 'cinquante',
        'soixante', 'soixante', 'quatre-vingt', 'quatre-vingt',
    ];

    /**
     * Convertit un nombre en toutes lettres (français, Gabon/Cameroun)
     */
    public static function convertir(float $montant, string $devise = 'FCFA'): string
    {
        $montant = (int) round($montant);

        if ($montant == 0) {
            return 'Zéro ' . self::libelleDevise($devise, true);
        }

        $partie_entiere = abs($montant);

        $lettres = self::enLettres($partie_entiere);

        // Majuscule première lettre
        $lettres = ucfirst($lettres);

        // Devise
        $lettres .= ' ' . self::libelleDevise($devise, $partie_entiere > 1);

        return $lettres;
    }

    private static function libelleDevise(string $devise, bool $pluriel = false): string
    {
        return match (strtoupper($devise)) {
            'EUR'  => $pluriel ? 'Euros' : 'Euro',
            'USD'  => $pluriel ? 'Dollars' : 'Dollar',
            default => 'Francs CFA', // FCFA toujours invariable
        };
    }

    private static function enLettres(int $n): string
    {
        if ($n === 0) return 'zéro';

        if ($n < 0) return 'moins ' . self::enLettres(-$n);

        $result = '';

        if ($n >= 1000000000) {
            $milliards = intdiv($n, 1000000000);
            $result .= self::enLettres($milliards) . ' milliard' . ($milliards > 1 ? 's' : '') . ' ';
            $n %= 1000000000;
        }

        if ($n >= 1000000) {
            $millions = intdiv($n, 1000000);
            $result .= self::enLettres($millions) . ' million' . ($millions > 1 ? 's' : '') . ' ';
            $n %= 1000000;
        }

        if ($n >= 1000) {
            $milliers = intdiv($n, 1000);
            if ($milliers === 1) {
                $result .= 'mille ';
            } else {
                $result .= self::enLettres($milliers) . ' mille ';
            }
            $n %= 1000;
        }

        if ($n >= 100) {
            $centaines = intdiv($n, 100);
            if ($centaines === 1) {
                $result .= 'cent ';
            } else {
                $result .= self::$unites[$centaines] . ' cent' . ($n % 100 === 0 ? 's' : '') . ' ';
            }
            $n %= 100;
        }

        if ($n > 0) {
            $result .= self::moinsDeCent($n) . ' ';
        }

        return rtrim($result);
    }

    private static function moinsDeCent(int $n): string
    {
        if ($n < 20) {
            return self::$unites[$n];
        }

        $d = intdiv($n, 10);
        $u = $n % 10;

        // Soixante-dix, soixante-onze...
        if ($d === 7) {
            return 'soixante-' . self::$unites[10 + $u];
        }

        // Quatre-vingt-dix...
        if ($d === 9) {
            return 'quatre-vingt-' . self::$unites[10 + $u];
        }

        // Quatre-vingts (sans s si suivi d'un autre chiffre)
        if ($d === 8) {
            if ($u === 0) return 'quatre-vingts';
            return 'quatre-vingt-' . self::$unites[$u];
        }

        $liaison = ($u === 1 && $d !== 8) ? '-et-' : ($u > 0 ? '-' : '');

        return self::$dizaines[$d] . $liaison . ($u > 0 ? self::$unites[$u] : '');
    }
}