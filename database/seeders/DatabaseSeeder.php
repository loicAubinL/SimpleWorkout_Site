<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkoutDay;
use App\Models\WorkoutSplit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
    'name' => 'Utilisateur démo',
    'email' => 'demo@example.test',
    'role' => User::ROLE_MEMBER,
]);

User::factory()->create([
    'name' => 'Responsable',
    'email' => 'responsable@example.test',
    'role' => User::ROLE_MANAGER,
]);

        WorkoutSplit::factory()
            ->count(12)
            ->has(
                WorkoutDay::factory()->count(3)
                    ->state(fn(array $attributes, WorkoutSplit $split) => ['user_id' => $split->user_id]),
                'workoutDays'
            )
            ->create(['user_id' => $user->id]);

        WorkoutSplit::factory()->create(['user_id' => $user->id, 'name' => 'Split vide']);
        WorkoutSplit::factory()->create(['name' => "Split d'un autre utilisateur"]);
    }
}
