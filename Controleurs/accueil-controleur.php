<?php

declare(strict_types=1);

function afficherAccueil(PDO $pdo): void
{
    $userId = 'a1111111-1111-1111-1111-111111111111';

    $exercices = [];
    $jour = null;
    $split = obtenirSplitActif($pdo, $userId);

    if ($split !== null) {
        $jour = obtenirJourActif($pdo, (string)$split['id'], (int)$split['current_position']);

        if ($jour !== null) {
            $exercices = obtenirExercicesDuJour($pdo, (string)$jour['id']);
        }
    }

    require __DIR__ . '/../Vues/accueil.php';
}