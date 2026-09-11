<?php

declare(strict_types=1);

function obtenirExercices(PDO $pdo): array {
    $requete = $pdo->prepare(
        'SELECT workout_day_id, name, notes, sub1, sub2, target_sets, target_reps, position, created_at, rir, subs
         FROM exercises
         ORDER BY created_at DESC, workout_day_id DESC'
    );
    $requete->execute();
    return $requete->fetchAll();
}

function obtenirSplitActif(PDO $pdo, string $userId): ?array {
    $requete = $pdo->prepare(
        'SELECT id, current_position FROM workout_splits WHERE user_id = :userId AND is_active = 1'
    );
    $requete->execute(['userId' => $userId]);
    return $requete->fetch() ?: null;
}

function obtenirJourActif(PDO $pdo, string $splitId, int $position): ?array {
    $requete = $pdo->prepare(
        'SELECT id, name FROM workout_days WHERE workout_split_id = :id AND position = :position'
    );
    $requete->execute(['id' => $splitId, 'position' => $position]);
    return $requete->fetch() ?: null;
}
function obtenirExercicesDuJour(PDO $pdo, string $jourId): array {
    $requete = $pdo->prepare(
        'SELECT  workout_day_id, name, notes, sub1, sub2, target_sets, target_reps, position, created_at, rir, subs FROM exercises WHERE workout_day_id = :jourId ORDER BY position'
    );
    $requete->execute(['jourId' => $jourId]);
    return $requete->fetchAll();
}