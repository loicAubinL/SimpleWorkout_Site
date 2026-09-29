<?php

declare(strict_types=1);

require_once __DIR__ . '/Modele.php';

class ExerciceModele extends Modele
{
    public function obtenirExercices(): array
    {
        return $this->executer(
            'SELECT workout_day_id, name, notes, sub1, sub2, target_sets, target_reps, position, created_at, rir, subs
             FROM exercises
             ORDER BY created_at DESC, workout_day_id DESC'
        )->fetchAll();
    }

    public function obtenirExercicesDuJour(string $jourId): array
    {
        return $this->executer(
            'SELECT id, workout_day_id, name, notes, sub1, sub2, target_sets, target_reps, position, created_at, rir, subs
             FROM exercises WHERE workout_day_id = :jourId ORDER BY position',
            ['jourId' => $jourId]
        )->fetchAll();
    }

    public function obtenirExerciceParId(string $id): ?array
    {
        return $this->executer(
            'SELECT id, workout_day_id, name, notes, target_sets, target_reps FROM exercises WHERE id = :id',
            ['id' => $id]
        )->fetch() ?: null;
    }

    public function modifier(string $id, string $nom, int $series, string $reps, string $notes): void
    {
        $this->executer(
            'UPDATE exercises
             SET name = :nom, target_sets = :series, target_reps = :reps, notes = :notes
             WHERE id = :id',
            ['id' => $id, 'nom' => $nom, 'series' => $series, 'reps' => $reps, 'notes' => $notes]
        );
    }
}
