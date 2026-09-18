<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modeles/logged-sets-modele.php';
require_once __DIR__ . '/../Modeles/workout-modele.php';

function afficherLoggedSetAjouter(PDO $pdo): void
{
    $exerciseId = trim($_POST['exercise_id'] ?? '');
    $setNumber = $_POST['set_number'] ?? '';
    $weight = $_POST['weight'] ?? '';
    $reps = $_POST['reps'] ?? '';

    if ($exerciseId === '' || $setNumber === '' || $weight === '' || $reps === '') {
        $_SESSION['erreur_formulaire'] = 'Tous les champs sont requis.';
        header('Location: index.php?action=accueil');
        exit;
    }

    $exercice = obtenirExerciceParId($pdo, $exerciseId);

    if ($exercice === null) {
        http_response_code(404);
        $titrePage = 'Exercice introuvable';
        $messageErreur = "L'exercice concerné n'existe pas.";
        require __DIR__ . '/../Vues/erreur.php';
        return;
    }

    $userId = 'a1111111-1111-1111-1111-111111111111';
    ajouterLoggedSet($pdo, $exerciseId, $userId, (int) $setNumber, (float) $weight, (int) $reps);

    header('Location: index.php?action=accueil');
    exit;
}
