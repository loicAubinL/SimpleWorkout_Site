<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutSplitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement(['Push Pull Legs', 'Upper Lower', 'Full Body', 'Arnold Split', 'Bro Split']),
            'is_active' => false,
            'current_position' => 0,
        ];
    }
}
