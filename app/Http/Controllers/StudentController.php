<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Lesson;
use App\Models\QuizResult;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // شاشة اختيار المادة
    public function dashboard()
    {
        $student = auth()->user();

        // جلب سجلات التقدم الخاصة بهذا الطالب حصراً وربطها بأسماء المواد
        $subjects_progress = \App\Models\StudentProgress::where('user_id', $student->id)
            ->with('subject')
            ->get();


        $subjects = Subject::all(); // نجلب المواد الحقيقية
        return view('student.dashboard', compact('subjects_progress'));
    }

    // شاشة خريطة الدروس لمادة معينة
    public function pathway($subjectId)
    {
        $user = auth()->user();
        $subject = \App\Models\Subject::with(['units.lessons.video'])->findOrFail($subjectId);

        // جلب كافة سجلات نتائج الاختبارات لهذا الطالب لنعرف الدروس المنتهية
        $completedLessonsIds = \App\Models\QuizResult::where('user_id', $user->id)
            ->pluck('video_id')
            ->toArray();

        // خوارزمية بسيطة لتحديد الدرس القادم المقترح:
        // هو أول درس لم ينجح الطالب في اختباره بعد.
        $recommendedLesson = null;
        $lessonsList = [];

        foreach ($subject->units as $unit) {
            foreach ($unit->lessons as $lesson) {
                $video = $lesson->video;
                if (!$video) continue;

                $status = 'locked';
                if (in_array($video->id, $completedLessonsIds)) {
                    $status = 'done';
                } elseif (!$recommendedLesson) {
                    $status = 'recommended';
                    $recommendedLesson = $lesson;
                }

                $lessonsList[] = [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'status' => $status,
                    'difficulty' => $video->difficulty ?? 'beginner'
                ];
            }
        }

        return view('student.pathway', compact('subject', 'lessonsList'));
    }

    // غرفة المشاهدة (Video + Tracking AI)
    public function viewingRoom(Lesson $lesson)
    {
        $video = $lesson->video; // نجلب الفيديو المرتبط بالدرس
        return view('student.viewing-room', compact('lesson', 'video'));
    }
    public function quiz(Lesson $lesson)
    {
        $questions = $lesson->video->questions; // جلب الأسئلة المرتبطة بفيديو الدرس
        return view('student.quiz', compact('questions', 'lesson'));
    }
    public function recordVideoWatch(Request $request)
    {
        $user = auth()->user();
        $videoId = $request->video_id;

        // 1. تسجيل التفاعل في جدول VideoInteraction (لو مش موجود)
        \App\Models\VideoInteraction::updateOrCreate(
            ['user_id' => $user->id, 'video_id' => $videoId],
            ['watch_time_seconds' => 600, 'updated_at' => now()] // محاكاة وقت مشاهدة كامل
        );

        return response()->json(['success' => true, 'message' => 'تم تسجيل مشاهدة الفيديو بنجاح!']);
    }

    public function submitQuiz(Request $request)
    {
        $user = auth()->user();
        $videoId = $request->video_id;
        $lessonId = $request->lesson_id;
        $accuracy = $request->accuracy;
        $avgTime = $request->avg_time;

        // 1. تسجيل نتيجة الاختبار في جدول النتائج
        QuizResult::updateOrCreate(
            ['user_id' => $user->id, 'video_id' => $videoId],
            ['accuracy_percentage' => $accuracy, 'average_response_time' => $avgTime]
        );

        // 2. تحديث سجل التقدم (Progress) للمادة
        $lesson = \App\Models\Lesson::find($lessonId);
        $subjectId = $lesson->unit->subject_id;

        $progress = StudentProgress::where('user_id', $user->id)
            ->where('subject_id', $subjectId)
            ->first();

        if ($progress) {
            // حساب نسبة جديدة (تبسيطاً سنزيدها بنسبة معينة بناءً على عدد دروس المادة)
            $totalLessons = \App\Models\Lesson::whereHas('unit', fn($q) => $q->where('subject_id', $subjectId))->count();
            $completedCount = QuizResult::where('user_id', $user->id)
                ->whereHas('video.lesson.unit', fn($q) => $q->where('subject_id', $subjectId))
                ->count();

            $percentage = ($completedCount / $totalLessons) * 100;

            $progress->update([
                'completed_lessons_count' => $completedCount,
                'completion_percentage' => $percentage,
                'overall_score' => $progress->overall_score + ($accuracy / 10), // زيادة سكور الطالب بناء على دقته
            ]);
        }

        return response()->json(['success' => true, 'message' => 'تم تحديث مهاراتك بنجاح!']);
    }
    public function achievements()
    {
        $student = auth()->user();

        // 1. حساب إحصائيات الوسام الأول: "المشاهد الشغوف" (عدد الفيديوهات المشاهدة)
        $videoCount = \App\Models\VideoInteraction::where('user_id', $student->id)->count();

        // 2. حساب إحصائيات الوسام الثاني: "عبقري الدقة" (كم مرة حصل على 100%)
        $perfectQuizzes = \App\Models\QuizResult::where('user_id', $student->id)
            ->where('accuracy_percentage', 100)
            ->count();

        // 3. حساب نقاط الإنجاز الكلية والترتيب (Ranking)
        $totalPoints = \App\Models\StudentProgress::where('user_id', $student->id)->sum('overall_score');

        // جلب ترتيب الطالب
        $allRankings = \App\Models\StudentProgress::select('user_id', \DB::raw('SUM(overall_score) as total_score'))
            ->groupBy('user_id')
            ->orderByDesc('total_score')
            ->get();

        $myRank = $allRankings->search(fn($item) => $item->user_id == $student->id) + 1;

        // جلب الأوائل (قائمة المتصدرين)
        $topStudents = \App\Models\User::where('role', 'student')
            ->where('grade_level', $student->grade_level)
            ->withSum('progress as total_points', 'overall_score')
            ->orderByDesc('total_points')
            ->take(5)
            ->get();

        return view('student.achievements', compact(
            'student',
            'videoCount',
            'perfectQuizzes',
            'totalPoints',
            'myRank',
            'topStudents'
        ));
    }
    public function profile()
    {
        // جلب اليوزر مع ملفه التعليمي (AI Profile)
        $user = auth()->user()->load('learningProfile');
        return view('student.profile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // 1. التحقق من البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // 2. تحديث الاسم
        $user->name = $request->name;
        $user->save();

        // 3. تحديث كلمة المرور إذا تم إدخالها
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'كلمة المرور الحالية غير صحيحة.');
            }
            $user->update(['password' => Hash::make($request->new_password)]);
        }

        return back()->with('success', 'تم تحديث بيانات ملفك بنجاح يا بطل!');
    }
}
