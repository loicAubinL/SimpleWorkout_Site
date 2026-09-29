<?php

declare(strict_types=1);

require_once __DIR__ . '/Modele.php';

class LoggedSetModele extends Modele
{
    public function obtenirLoggedSetsParExercice(string $exerciceId): array
    {
        return $this->executer(
            'SELECT id, exercise_id, user_id, set_number, weight, reps FROM logged_sets WHERE exercise_id = :exerciceId ORDER BY set_number',
            ['exerciceId' => $exerciceId]
        )->fetchAll();
    }

    public function obtenirLoggedSetParId(string $id): ?array
    {
        return $this->executer(
            'SELECT id, exercise_id, set_number, weight, reps FROM logged_sets WHERE id = :id',
            ['id' => $id]
        )->fetch() ?: null;
    }

    public function ajouterLoggedSet(string $exerciseId, string $userId, int $setNumber, float $weight, int $reps): void
    {
        $this->executer(
            'INSERT INTO logged_sets (exercise_id, user_id, set_number, weight, reps)
             VALUES (:exerciseId, :userId, :setNumber, :weight, :reps)',
            [
                'exerciseId' => $exerciseId,
                'userId' => $userId,
                'setNumber' => $setNumber,
                'weight' => $weight,
                'reps' => $reps,
            ]
        );
    }

    public function supprimerLoggedSet(string $id): void
    {
        $this->executer('DELETE FROM logged_sets WHERE id = :id', ['id' => $id]);
    }
}