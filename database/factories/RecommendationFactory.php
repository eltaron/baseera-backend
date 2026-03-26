<?php

namespace Database\Factories;

use App\Models\Recommendation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recommendation>
 */
class RecommendationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reasons = [
            'لأنك أبدعت في مهارة الجمع',
            'نقترح هذا لتقوية نقاط الضعف في القسمة',
            'بناءً على تفضيلك للتعلم البصري',
            'محتوى إضافي للمتفوقين'
        ];
        return [
            'user_id' => \App\Models\User::factory(),
            'video_id' => \App\Models\Video::factory(),
            'reason' => fake()->randomElement($reasons),
            'is_viewed' => fake()->boolean(),
        ];
    }
}
