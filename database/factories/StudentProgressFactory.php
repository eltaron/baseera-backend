<?php

namespace Database\Factories;

use App\Models\StudentProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentProgress>
 */
class StudentProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'subject_id' => \App\Models\Subject::factory(),
            'completed_lessons_count' => fake()->numberBetween(1, 20),
            'overall_score' => fake()->randomFloat(2, 60, 100),
            'completion_percentage' => fake()->randomFloat(2, 10, 100),
        ];
    }
}
