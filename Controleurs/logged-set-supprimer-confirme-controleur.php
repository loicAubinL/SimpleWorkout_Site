<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modeles/logged-sets-modele.php';

function afficherLoggedSetSupprimerConfirme(PDO $pdo): void
{
    $id = trim($_POST['id'] ?? '');

    if ($id === '') {
        http_response_code(400);
        $titrePage = 'Identifiant manquant';
        $messageErreur = 'Aucun identifiant fourni.';
        require __DIR__ . '/../Vues/erreur.php';
        return;
    }

    $set = obtenirLoggedSetParId($pdo, $id);

    if ($set === null) {
        http_response_code(404);
        $titrePage = 'Série introuvable';
        $messageErreur = "Cette série n'existe pas ou a déjà été supprimée.";
        require __DIR__ . '/../Vues/erreur.php';
        return;
    }

    supprimerLoggedSet($pdo, $id);

    header('Location: index.php?action=exercice-detail&id=' . urlencode($set['exercise_id']));
    exit;
}
