<?php
declare(strict_types=1);

try {
    $pdo = require __DIR__ . '/config/bd.php';
require_once __DIR__ . '/Modeles/exercice-modele.php';

    $action = $_GET['action'] ?? 'accueil';

    switch ($action) {
        case 'accueil':
            require __DIR__ . '/Controleurs/accueil-controleur.php';
            afficherAccueil($pdo);
            break;


        case 'exercice':
            require __DIR__ . '/Controleurs/exercice-controleur.php';
            afficherExercices($pdo);
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