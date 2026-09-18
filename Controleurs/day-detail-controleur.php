<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modeles/workout-modele.php';

function afficherDayDetail(PDO $pdo, string $dayId): void
{
    $jour = obtenirDayDetail($pdo, $dayId);
    if (!$jour) {
        http_response_code(404);
        $titrePage = 'Jour non trouvé';
        $messageErreur = "Le jour demandé n'existe pas.";
        require __DIR__ . '/../Vues/erreur.php';
        return;
    }

    $exercices = obtenirExercicesDuJour($pdo, $dayId);
    $titrePage = $jour['name'];
    require __DIR__ . '/../Vues/workout/exercice.php';
}
