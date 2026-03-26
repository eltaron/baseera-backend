<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skills = ['الجمع', 'الطرح', 'القراءة السريعة', 'القواعد النحوية', 'الكسور', 'الاستماع', 'المحادثة', 'الضرب والقسمة'];
        return [
            'subject_id' => \App\Models\Subject::factory(),
            'name' => fake()->randomElement($skills),
        ];
    }
}
