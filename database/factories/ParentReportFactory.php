<?php

namespace Database\Factories;

use App\Models\ParentReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ParentReport>
 */
class ParentReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $summaries = [
            'ابنكم أبدى تركيزاً رائعاً في مادة الرياضيات اليوم، وننصح بالاستمرار على هذا النهج.',
            'لوحظ بعض التشتت في نهاية فيديو اللغة العربية، ربما يحتاج الطفل لاستراحة أطول.',
            'أداء ممتاز في حل الأسئلة السريعة، سرعة الاستيعاب لديه تتطور بشكل ملحوظ.',
            'هناك صعوبة بسيطة في استيعاب مفاهيم القسمة، قمنا باقتراح فيديوهات أبسط.'
        ];
        return [
            'user_id' => \App\Models\User::factory(),
            'summary_text' => fake()->randomElement($summaries),
            'status_color' => fake()->randomElement(['green', 'yellow', 'red']),
            'report_date' => fake()->date(),
        ];
    }
}
