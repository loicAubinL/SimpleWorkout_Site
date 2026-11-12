<?php

namespace Database\Factories;

use App\Models\WorkoutDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'workout_day_id' => WorkoutDay::factory(),
            'name' => fake()->randomElement(['Développé couché', 'Squat', 'Soulevé de terre', 'Tractions', 'Rowing barre', 'Développé militaire']),
            'target_sets' => fake()->numberBetween(2, 5),
            'target_reps' => (string) fake()->randomElement([5, 8, 10, 12]),
            'weight' => fake()->randomElement([null, 20, 40, 60, 80]),
            'rest_seconds' => fake()->randomElement([60, 90, 120, 180]),
            'position' => 0,
        ];
    }
}
