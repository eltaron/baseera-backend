<?php

namespace Database\Factories;

use App\Models\LearningProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LearningProfile>
 */
class LearningProfileFactory extends Factory
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
            'current_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'strengths' => [fake('ar_SA')->word(), fake('ar_SA')->word()],
            'weaknesses' => [fake('ar_SA')->word()],
            'preferred_learning_style' => fake()->randomElement(['بصري (Visual)', 'سمعي (Auditory)', 'حركي (Kinesthetic)']),
        ];
    }
}
