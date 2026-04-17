<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Lesson;
use App\Models\StudentProgress;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // شاشة اختيار المادة
    public function dashboard()
    {
        $subjects = Subject::all(); // نجلب المواد الحقيقية
        return view('student-dashboard', compact('subjects'));
    }

    // شاشة خريطة الدروس لمادة معينة
    public function pathway($subjectId)
    {
        $subject = Subject::with('units.lessons')->findOrFail($subjectId);
        return view('student-pathway', compact('subject'));
    }

    // غرفة المشاهدة (Video + Tracking AI)
    public function viewingRoom(Lesson $lesson)
    {
        $video = $lesson->video; // نجلب الفيديو المرتبط بالدرس
        return view('student-viewing-room', compact('lesson', 'video'));
    }

    public function quiz(Lesson $lesson)
    {
        $questions = $lesson->video->questions; // جلب الأسئلة المرتبطة بفيديو الدرس
        return view('student-quiz', compact('questions', 'lesson'));
    }

    public function achievements()
    {
        $user = auth()->user();
        $achievements = $user->learningProfile; // بيانات الـ AI
        return view('student-achievements', compact('user', 'achievements'));
    }
}
