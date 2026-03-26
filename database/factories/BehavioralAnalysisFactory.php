<?php

namespace Database\Factories;

use App\Models\BehavioralAnalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BehavioralAnalysis>
 */
class BehavioralAnalysisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'focus_level' => fake()->randomFloat(2, 50, 100),
            'confusion_level' => fake()->randomFloat(2, 0, 30),
            'boredom_level' => fake()->randomFloat(2, 0, 20),
            'detected_learning_style' => fake()->randomElement(['Visual', 'Auditory', 'Kinesthetic']),
        ];
    }
}
