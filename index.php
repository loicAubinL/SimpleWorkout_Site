<?php

declare(strict_types=1);

try {
    $pdo = require __DIR__ . '/config/bd.php';
    require_once __DIR__ . '/Modeles/workout-modele.php';

    $action = $_GET['action'] ?? 'accueil';

    switch ($action) {
        case 'accueil':
            require __DIR__ . '/Controleurs/accueil-controleur.php';
            afficherAccueil($pdo);
            break;


        case 'workout-detail':
            require __DIR__ . '/Controleurs/workout-detail-controleur.php';
            $id = $_GET['id'] ?? null;
            if ($id !== null) {
                afficherWorkoutDetail($pdo, $id);
            } else {
                http_response_code(404);
                $titrePage = 'Page non trouvée';
                $messageErreur = "Identifiant de workout manquant.";
                require __DIR__ . '/Vues/erreur.php';
            }
            break;

        case 'day-detail':
            require __DIR__ . '/Controleurs/day-detail-controleur.php';
            $id = $_GET['id'] ?? null;
            if ($id !== null) {
                afficherDayDetail($pdo, $id);
            } else {
                http_response_code(404);
                $titrePage = 'Page non trouvée';
                $messageErreur = "Identifiant de jour manquant.";
                require __DIR__ . '/Vues/erreur.php';
            }
            break;


        case 'workouts':
            require __DIR__ . '/Controleurs/workout-controleur.php';
            afficherWorkouts($pdo);
            break;

        case 'recits':
            require __DIR__ . '/Controleurs/recits-controleur.php';
            afficherRecits();
            break;

        default:
            http_response_code(404);
            $titrePage = 'Page non trouvée';
            $messageErreur = "La page demandée n'existe pas.";
            require __DIR__ . '/Vues/erreur.php';
            break;
    }
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    $titrePage = 'Erreur';
    $messageErreur = 'Une erreur est survenue. Veuillez réessayer plus tard.';
    require __DIR__ . '/Vues/erreur.php';
}
