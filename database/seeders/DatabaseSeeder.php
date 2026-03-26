<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Skill;
use App\Models\Unit;
use App\Models\Lesson;
use App\Models\Video;
use App\Models\Question;
use App\Models\VideoInteraction;
use App\Models\QuizResult;
use App\Models\BehavioralAnalysis;
use App\Models\LearningProfile;
use App\Models\Recommendation;
use App\Models\ParentReport;
use App\Models\StudentProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. إنشاء حساب مدير النظام (Admin) للدخول إلى لوحة Filament
        User::create([
            'name' => 'Admin Baseera',
            'email' => 'admin@baseera.ai',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. إنشاء الصفوف الدراسية الستة (بيانات أساسية)
        $gradeNames = ['الأول الابتدائي', 'الثاني الابتدائي', 'الثالث الابتدائي', 'الرابع الابتدائي', 'الخامس الابتدائي', 'السادس الابتدائي'];
        $grades = [];
        foreach ($gradeNames as $name) {
            $grades[] = Grade::create(['name' => $name]);
        }

        // 3. إنشاء المواد الدراسية الثلاث (بيانات أساسية)
        $subjectNames = ['اللغة العربية', 'اللغة الإنجليزية', 'الرياضيات'];
        $subjects = [];
        foreach ($subjectNames as $name) {
            $subjects[] = Subject::create(['name' => $name]);
        }

        // 4. إنشاء المهارات لكل مادة (مثلاً 5 مهارات لكل مادة = 15 مهارة)
        foreach ($subjects as $subject) {
            Skill::factory(5)->create(['subject_id' => $subject->id]);
        }

        // 5. بناء هيكل المحتوى التعليمي (وحدات -> دروس -> فيديوهات -> أسئلة)
        // سنقوم بإنشاء محتوى لكل مادة في كل صف
        foreach ($subjects as $subject) {
            foreach ($grades as $grade) {
                // إنشاء 3 وحدات لكل مادة في كل صف
                $units = Unit::factory(3)->create([
                    'subject_id' => $subject->id,
                    'grade_id' => $grade->id
                ]);

                foreach ($units as $unit) {
                    // إنشاء 3 دروس لكل وحدة
                    $lessons = Lesson::factory(3)->create(['unit_id' => $unit->id]);

                    foreach ($lessons as $lesson) {
                        // إنشاء فيديو واحد لكل درس
                        $video = Video::factory()->create([
                            'lesson_id' => $lesson->id,
                            'skill' => Skill::where('subject_id', $subject->id)->get()->random()->name
                        ]);

                        // إنشاء 3 أسئلة لكل فيديو
                        Question::factory(3)->create(['video_id' => $video->id]);
                    }
                }
            }
        }

        // 6. إنشاء أولياء الأمور والطلاب (100 ولي أمر و 200 طالب)
        User::factory(100)->create(['role' => 'parent'])->each(function ($parent) use ($subjects) {
            // كل ولي أمر لديه طفلين
            $students = User::factory(2)->create([
                'role' => 'student',
                'parent_id' => $parent->id,
                'grade_level' => rand(1, 6)
            ]);

            foreach ($students as $student) {
                // إنشاء ملف تعليمي لكل طالب
                LearningProfile::factory()->create(['user_id' => $student->id]);

                // إنشاء سجل تقدم لكل طالب في كل مادة
                foreach ($subjects as $subject) {
                    StudentProgress::factory()->create([
                        'user_id' => $student->id,
                        'subject_id' => $subject->id
                    ]);
                }

                // محاكاة نشاط الطالب (شاهد 5 فيديوهات عشوائية)
                $randomVideos = Video::all()->random(5);
                foreach ($randomVideos as $video) {
                    // سجل تفاعل
                    VideoInteraction::factory()->create([
                        'user_id' => $student->id,
                        'video_id' => $video->id
                    ]);

                    // نتيجة اختبار
                    QuizResult::factory()->create([
                        'user_id' => $student->id,
                        'video_id' => $video->id
                    ]);

                    // تحليل ذكاء اصطناعي (مشاعر)
                    BehavioralAnalysis::factory()->create([
                        'user_id' => $student->id,
                        'video_id' => $video->id
                    ]);

                    // توصية مقترحة
                    Recommendation::factory()->create([
                        'user_id' => $student->id,
                        'video_id' => $video->id
                    ]);
                }

                // إنشاء 3 تقارير دورية لولي الأمر عن هذا الطالب
                ParentReport::factory(3)->create(['user_id' => $student->id]);
            }
        });
    }
}
