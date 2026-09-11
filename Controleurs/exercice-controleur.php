<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modeles/exercice-modele.php';

function afficherExercices(PDO $pdo): void
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

    $aUnSplitActif = ($split !== null && $jour !== null);
    $titrePage = $aUnSplitActif ? "Workout - " . $jour['name'] : "Accueil Workout";

    require __DIR__ . '/../Vues/exercice/index.php';
}