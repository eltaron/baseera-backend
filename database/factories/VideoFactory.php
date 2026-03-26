<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'درس ' . fake('ar_SA')->realText(20),
            'video_url' => 'https://www.youtube.com/watch?v=sample',
            'skill' => fake()->word(),
            'difficulty' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'duration_seconds' => fake()->numberBetween(300, 1200),
        ];
    }
}
