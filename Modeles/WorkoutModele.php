<?php

declare(strict_types=1);

require_once __DIR__ . '/Modele.php';

class WorkoutModele extends Modele
{
    public function obtenirWorkouts(string $userId): array
    {
        return $this->executer(
            'SELECT id, name, is_active FROM workout_splits WHERE user_id = :userId ORDER BY created_at DESC',
            ['userId' => $userId]
        )->fetchAll();
    }

    public function obtenirWorkoutDetail(string $id): ?array
    {
        return $this->executer(
            'SELECT id, name, is_active FROM workout_splits WHERE id = :id',
            ['id' => $id]
        )->fetch() ?: null;
    }

    public function obtenirSplitActif(string $userId): ?array
    {
        return $this->executer(
            'SELECT id, current_position FROM workout_splits WHERE user_id = :userId AND is_active = 1',
            ['userId' => $userId]
        )->fetch() ?: null;
    }

    public function obtenirJoursDuWorkout(string $workoutId): array
    {
        return $this->executer(
            'SELECT id, name, position FROM workout_days WHERE workout_split_id = :workoutId ORDER BY position',
            ['workoutId' => $workoutId]
        )->fetchAll();
    }

    public function obtenirJourActif(string $splitId, int $position): ?array
    {
        return $this->executer(
            'SELECT id, name FROM workout_days WHERE workout_split_id = :id AND position = :position',
            ['id' => $splitId, 'position' => $position]
        )->fetch() ?: null;
    }

    public function obtenirDayDetail(string $id): ?array
    {
        return $this->executer(
            'SELECT id, name, is_rest_day FROM workout_days WHERE id = :id',
            ['id' => $id]
        )->fetch() ?: null;
    }
}