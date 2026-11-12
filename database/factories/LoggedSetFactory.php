<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\LoggedSet>
 */
class LoggedSetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exercise_id' => Exercise::factory(),
            'user_id' => User::factory(),
            'set_number' => 1,
            'weight' => fake()->randomElement([40, 50, 60]),
            'reps' => fake()->numberBetween(5, 12),
            'logged_at' => now(),
        ];
    }
}
