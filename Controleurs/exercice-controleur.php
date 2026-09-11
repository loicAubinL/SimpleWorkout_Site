<?php

declare(strict_types=1);

function afficherExercice(PDO $pdo): void
{
    $userId = 'a1111111-1111-1111-1111-111111111111'; 

    $exercices = [];
    $jour = null;
    $split = obtenirSplitActif($pdo, $userId);

    if ($split !== null) {
        $jour = obtenirJourActif($pdo, $split['id'], $split['current_position']);

        if ($jour !== null) {
            $exercices = obtenirExercicesDuJour($pdo, $jour['id']);
        }
    }

    $titrePage = $aUnSplitActif ? "Workout - " . $jour['name'] : "Accueil Workout";

    require __DIR__ . '/../Vues/exercice/index.php';
}