<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutSplit;

class WorkoutSplitPolicy
{
    /**
     * Le responsable peut consulter, modifier et supprimer tous les workouts.
     */
    public function before(?User $user, string $ability): ?bool
    {
        if ($user?->isManager() && in_array($ability, ['view', 'update', 'delete'], true)) {
            return true;
        }

        return null;
    }

    /**
     * Un workout public est visible par tous (même un visiteur) ; un workout privé, par son propriétaire.
     */
    public function view(?User $user, WorkoutSplit $workoutSplit): bool
    {
        return $workoutSplit->is_public || $workoutSplit->user_id === $user?->id;
    }

    /**
     * Modifier le workout, ses jours et ses exercices : propriétaire seulement.
     */
    public function update(User $user, WorkoutSplit $workoutSplit): bool
    {
        return $workoutSplit->user_id === $user->id;
    }

    public function delete(User $user, WorkoutSplit $workoutSplit): bool
    {
        return $this->update($user, $workoutSplit);
    }

    /**
     * Sauvegarder dans sa liste : seulement un workout public (le sien ou celui d'un autre).
     */
    public function save(User $user, WorkoutSplit $workoutSplit): bool
    {
        return $workoutSplit->is_public;
    }

    /**
     * Copier dans ses workouts : tout workout qu'on a le droit de voir.
     */
    public function copy(User $user, WorkoutSplit $workoutSplit): bool
    {
        return $this->view($user, $workoutSplit);
    }

    /**
     * Faire une séance : seulement sur ses propres workouts.
     */
    public function train(User $user, WorkoutSplit $workoutSplit): bool
    {
        return $workoutSplit->user_id === $user->id;
    }
}
