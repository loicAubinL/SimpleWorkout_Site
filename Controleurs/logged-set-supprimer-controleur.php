<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/securite.php';
require_once __DIR__ . '/../Modeles/logged-sets-modele.php';

function afficherLoggedSetSupprimer(PDO $pdo, string $id): void
{
    $set = obtenirLoggedSetParId($pdo, $id);

    if ($set === null) {
        http_response_code(404);
        $titrePage = 'Série introuvable';
        $messageErreur = "Cette série n'existe pas ou a déjà été supprimée.";
        require __DIR__ . '/../Vues/erreur.php';
        return;
    }

    require __DIR__ . '/../Vues/workout/confirmer-suppression.php';
}
