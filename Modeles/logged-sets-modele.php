<?php

declare(strict_types=1);

function obtenirLoggedSetsParExercice(PDO $pdo, string $exerciceId): array {
    
    $requete = $pdo->prepare(
        'SELECT id, exercise_id, user_id, set_number, weight, reps FROM logged_sets WHERE exercise_id = :exerciceId ORDER BY set_number'
    );
    $requete->execute(['exerciceId' => $exerciceId]);
    return $requete->fetchAll();
}

function ajouterLoggedSet(PDO $pdo, string $exerciseId, string $userId, int $setNumber, float $weight, int $reps): void {
    $requete = $pdo->prepare(
        'INSERT INTO logged_sets (exercise_id, user_id, set_number, weight, reps) VALUES (:exerciseId, :userId, :setNumber, :weight, :reps)'
    );
    $requete->execute([
        'exerciseId' => $exerciseId,
        'userId' => $userId,
        'setNumber' => $setNumber,
        'weight' => $weight,
        'reps' => $reps,
    ]);
}

function supprimerLoggedSet(PDO $pdo, string $id): void {
    $requete = $pdo->prepare('DELETE FROM logged_sets WHERE id = :id');
    $requete->execute(['id' => $id]);
}

function obtenirLoggedSetParId(PDO $pdo, string $id): ?array {
    $requete = $pdo->prepare('SELECT id, exercise_id, set_number, weight, reps FROM logged_sets WHERE id = :id');
    $requete->execute(['id' => $id]);
    return $requete->fetch() ?: null;
}