<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $options = [fake('ar_SA')->word(), fake('ar_SA')->word(), fake('ar_SA')->word(), fake('ar_SA')->word()];
        return [
            'video_id' => \App\Models\Video::factory(),
            'question_text' => fake('ar_SA')->realText(50) . '؟',
            'options' => $options,
            'correct_answer' => $options[0], // نعتبر الخيار الأول هو الصحيح دائماً للتسهيل
        ];
    }
}
