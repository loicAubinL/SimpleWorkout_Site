<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkoutSplit;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutDayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'workout_split_id' => WorkoutSplit::factory(),
            'name' => fake()->randomElement(['Push', 'Pull', 'Legs', 'Upper', 'Lower', 'Full Body']),
            'day_of_week' => fake()->numberBetween(1, 7),
            'position' => 0,
            'is_rest_day' => false,
        ];
    }
}
