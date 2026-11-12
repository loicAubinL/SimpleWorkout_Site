<?php

namespace Tests;

use App\Models\Exercise;
use App\Models\User;
use App\Models\WorkoutDay;
use App\Models\WorkoutSplit;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Crée un split avec un jour et un exercice pour $user.
     *
     * @return array{0: WorkoutSplit, 1: WorkoutDay, 2: Exercise}
     */
    protected function creerSplit(User $user, array $attributs = []): array
    {
        $split = WorkoutSplit::factory()->create(['user_id' => $user->id, ...$attributs]);
        $jour = WorkoutDay::factory()->create([
            'user_id' => $user->id,
            'workout_split_id' => $split->id,
            'name' => 'Push',
            'position' => 0,
        ]);
        $exercice = Exercise::factory()->create([
            'workout_day_id' => $jour->id,
            'name' => 'Développé couché',
            'target_sets' => 3,
            'target_reps' => '8',
            'weight' => 60,
        ]);

        return [$split, $jour, $exercice];
    }

    protected function responsable(): User
    {
        return User::factory()->create(['role' => User::ROLE_MANAGER]);
    }
}
