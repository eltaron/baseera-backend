<?php

namespace Database\Factories;

use App\Models\QuizResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizResult>
 */
class QuizResultFactory extends Factory
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
            'video_id' => \App\Models\Video::factory(),
            'accuracy_percentage' => fake()->randomFloat(2, 50, 100),
            'average_response_time' => fake()->randomFloat(2, 5, 30),
        ];
    }
}
