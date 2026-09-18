<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modeles/workout-modele.php';

function afficherWorkouts(PDO $pdo): void
{
    $userId = 'a1111111-1111-1111-1111-111111111111'; 
    $workouts = obtenirWorkouts($pdo, $userId);
    $titrePage = 'Mes Workouts';
    require __DIR__ . '/../Vues//workout/index.php';
}