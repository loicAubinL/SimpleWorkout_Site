<?php

namespace App\Support;

class Formatage
{
    /**
     * 72.50 → « 72,5 kg » (fr) ou « 72.5 kg » (en) ; null → null.
     */
    public static function charge(float|string|null $valeur, bool $avecUnite = true): ?string
    {
        if ($valeur === null || $valeur === '') {
            return null;
        }

        $separateur = app()->getLocale() === 'fr' ? ',' : '.';
        $texte = rtrim(rtrim(number_format((float) $valeur, 2, $separateur, ''), '0'), $separateur);

        return $avecUnite ? $texte . ' kg' : $texte;
    }

    /**
     * 90 → « 1 min 30 » ; 60 → « 1 min » ; 45 → « 45 s ».
     */
    public static function repos(?int $secondes): ?string
    {
        if ($secondes === null) {
            return null;
        }

        $minutes = intdiv($secondes, 60);
        $reste = $secondes % 60;

        if ($minutes === 0) {
            return $reste . ' s';
        }

        return $reste === 0 ? $minutes . ' min' : sprintf('%d min %02d', $minutes, $reste);
    }
}
