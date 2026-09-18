<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modeles/workout-modele.php';
require_once __DIR__ . '/../Modeles/logged-sets-modele.php';

function afficherLoggedSets(PDO $pdo, string $exerciceId): void
{
    $exercice = obtenirExerciceParId($pdo, $exerciceId);
    if (!$exercice) {
        http_response_code(404);
        $titrePage = 'Exercice non trouvé';
        $messageErreur = "L'exercice demandé n'existe pas.";
        require __DIR__ . '/../Vues/erreur.php';
        return;
    }

    $loggedSets = obtenirLoggedSetsParExercice($pdo, $exerciceId);
    $titrePage = $exercice['name'];
    require __DIR__ . '/../Vues/workout/loggedSets.php';
}
