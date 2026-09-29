<?php

declare(strict_types=1);

require_once __DIR__ . '/config/securite.php';
require_once __DIR__ . '/config/url.php';
require_once __DIR__ . '/Modeles/WorkoutModele.php';
require_once __DIR__ . '/Modeles/ExerciceModele.php';
require_once __DIR__ . '/Modeles/LoggedSetModele.php';
require_once __DIR__ . '/Vues/Vue.php';
require_once __DIR__ . '/Controleurs/ControleurErreur.php';
require_once __DIR__ . '/Controleurs/ControleurAccueil.php';
require_once __DIR__ . '/Controleurs/ControleurRecits.php';
require_once __DIR__ . '/Controleurs/ControleurWorkout.php';
require_once __DIR__ . '/Controleurs/ControleurLoggedSet.php';
require_once __DIR__ . '/Routage/Routeur.php';

demarrerSession();

$vue = new Vue();
$controleurErreur = new ControleurErreur($vue);

try {
    $pdo = require __DIR__ . '/config/bd.php';

    $workouts = new WorkoutModele($pdo);
    $exercices = new ExerciceModele($pdo);
    $loggedSets = new LoggedSetModele($pdo);

    $routeur = new Routeur(
        new ControleurAccueil($workouts, $exercices, $vue),
        new ControleurRecits($vue),
        new ControleurWorkout($workouts, $exercices, $vue, $controleurErreur),
        new ControleurLoggedSet($exercices, $loggedSets, $vue, $controleurErreur),
        $controleurErreur
    );

    $routeur->router();
} catch (Throwable $exception) {
    $statut = in_array($exception->getCode(), [400, 403, 404, 405], true)
        ? $exception->getCode()
        : 500;
    $message = $statut === 500
        ? 'Une erreur empêche le traitement de la demande.'
        : $exception->getMessage();

    if ($statut === 500) {
        error_log($exception->getMessage());
    }

    $controleurErreur->afficher($message, $statut);
}