<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modeles/workout-modele.php';

function afficherWorkoutDetail(PDO $pdo, string $workoutId): void
{
    $workout = obtenirWorkoutDetail($pdo, $workoutId);
    if (!$workout) {
        http_response_code(404);
        $titrePage = 'Workout non trouvé';
        $messageErreur = "Le workout demandé n'existe pas.";
        require __DIR__ . '/../Vues/erreur.php';
        return;
    }

    $jours = obtenirJoursDuWorkout($pdo, $workoutId);
    $titrePage = $workout['name'];
    require __DIR__ . '/../Vues/workout/day.php';
}