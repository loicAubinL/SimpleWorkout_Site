<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Auth\Access\Response;

class WorkoutSplitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkoutSplit $workoutSplit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkoutSplit $workoutSplit): bool
    {
        return $workoutSplit->user_id === $user->id;
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkoutSplit $workoutSplit): bool
    {
        return $this->update($user, $workoutSplit);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkoutSplit $workoutSplit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkoutSplit $workoutSplit): bool
    {
        return false;
    }
}
