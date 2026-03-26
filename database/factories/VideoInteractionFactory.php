<?php

namespace Database\Factories;

use App\Models\VideoInteraction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VideoInteraction>
 */
class VideoInteractionFactory extends Factory
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
            'watch_time_seconds' => fake()->numberBetween(60, 600),
            'replay_count' => fake()->numberBetween(0, 5),
            'pause_frequency' => fake()->numberBetween(0, 10),
        ];
    }
}
