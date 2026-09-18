<?php

declare(strict_types=1);

require_once __DIR__ . '/config/securite.php';
demarrerSession();

try {
    $pdo = require __DIR__ . '/config/bd.php';
    require_once __DIR__ . '/Modeles/workout-modele.php';
    require_once __DIR__ . '/Modeles/logged-sets-modele.php';

    $action = $_GET['action'] ?? 'accueil';

    switch ($action) {
        case 'accueil':
            require __DIR__ . '/Controleurs/accueil-controleur.php';
            afficherAccueil($pdo);
            break;

        case 'workout-detail':
            require __DIR__ . '/Controleurs/workout-detail-controleur.php';
            $id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW);
            if ($id !== null && $id !== false && $id !== '') {
                afficherWorkoutDetail($pdo, $id);
            } else {
                http_response_code(400);
                $titrePage = 'Identifiant invalide';
                $messageErreur = "Identifiant de workout manquant ou invalide.";
                require __DIR__ . '/Vues/erreur.php';
            }
            break;

        case 'day-detail':
            require __DIR__ . '/Controleurs/day-detail-controleur.php';
            $id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW);
            if ($id !== null && $id !== false && $id !== '') {
                afficherDayDetail($pdo, $id);
            } else {
                http_response_code(400);
                $titrePage = 'Identifiant invalide';
                $messageErreur = "Identifiant de jour manquant ou invalide.";
                require __DIR__ . '/Vues/erreur.php';
            }
            break;

        case 'exercice-detail':
            require __DIR__ . '/Controleurs/loggedSets-controleur.php';
            $id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW);
            if ($id !== null && $id !== false && $id !== '') {
                afficherLoggedSets($pdo, $id);
            } else {
                http_response_code(400);
                $titrePage = 'Identifiant invalide';
                $messageErreur = "Identifiant d'exercice manquant ou invalide.";
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

        // --- Actions d'écriture : méthode + CSRF validés ICI, dans le routeur ---

        case 'logged-set-ajouter':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                $titrePage = 'Méthode non autorisée';
                $messageErreur = "Cette action nécessite un formulaire.";
                require __DIR__ . '/Vues/erreur.php';
                break;
            }
            if (!verifierJetonCsrf($_POST['csrf_token'] ?? null)) {
                http_response_code(403);
                $titrePage = 'Requête refusée';
                $messageErreur = "Jeton de sécurité invalide.";
                require __DIR__ . '/Vues/erreur.php';
                break;
            }
            require __DIR__ . '/Controleurs/logged-set-ajouter-controleur.php';
            afficherLoggedSetAjouter($pdo);
            break;

        case 'logged-set-supprimer':
            require __DIR__ . '/Controleurs/logged-set-supprimer-controleur.php';
            $id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW);
            if ($id !== null && $id !== false && $id !== '') {
                afficherLoggedSetSupprimer($pdo, $id);
            } else {
                http_response_code(400);
                $titrePage = 'Identifiant invalide';
                $messageErreur = "Identifiant manquant ou invalide.";
                require __DIR__ . '/Vues/erreur.php';
            }
            break;

        case 'logged-set-supprimer-confirme':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                $titrePage = 'Méthode non autorisée';
                $messageErreur = "Cette action nécessite un formulaire.";
                require __DIR__ . '/Vues/erreur.php';
                break;
            }
            if (!verifierJetonCsrf($_POST['csrf_token'] ?? null)) {
                http_response_code(403);
                $titrePage = 'Requête refusée';
                $messageErreur = "Jeton de sécurité invalide.";
                require __DIR__ . '/Vues/erreur.php';
                break;
            }
            require __DIR__ . '/Controleurs/logged-set-supprimer-confirme-controleur.php';
            afficherLoggedSetSupprimerConfirme($pdo);
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
    http_response_code(500);
    $titrePage = 'Erreur';
    $messageErreur = 'Une erreur est survenue. Veuillez réessayer plus tard.';
    require __DIR__ . '/Vues/erreur.php';
}
