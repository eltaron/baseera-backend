<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LearningProfile;
use App\Models\StudentProgress;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * 1. عرض صفحات الواجهة (Views)
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showForgetPassword()
    {
        return view('auth.forget-password');
    }

    /**
     * 2. منطق تسجيل الدخول (Login Logic)
     */
    public function login(Request $request)
    {
        // التحقق من المدخلات
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
            'role'     => 'required|in:student,parent,admin', // نستخدم الـ Toggle اللي برمجناه في الفرونت
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // التأكد أن الشخص الذي سجل يحمل الرتبة المختارة من واجهة الـ UI
            if ($user->role !== $request->role) {
                Auth::logout();
                return back()->with('error', 'هذا الحساب لا يملك صلاحية الدخول كـ ' . ($request->role == 'student' ? 'طالب' : 'ولي أمر') . '.');
            }

            // التوجيه الذكي بناءً على الرتبة
            return $this->redirectBasedOnRole($user);
        }

        // في حال فشل تسجيل الدخول
        return back()->with('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة، يرجى المحاولة مرة أخرى.');
    }

    /**
     * 3. منطق إنشاء حساب جديد (Register Logic)
     * ملاحظة: هنا سننشئ حساب الأب، وحساب الابن، والـ AI Profile للابن في آن واحد!
     */
    public function register(Request $request)
    {
        $request->validate([
            'parent_name' => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'password'    => 'required|string|min:8|confirmed',
            'child_name'  => 'required|string|max:255',
            'grade'       => 'required|integer|between:1,6',
        ], [
            'email.unique' => 'هذا البريد الإلكتروني مسجل لدينا بالفعل، جرب تسجيل الدخول.',
            'password.min' => 'يجب أن لا تقل كلمة المرور عن 8 أحرف لضمان أمان حسابكم.',
        ]);

        // أ- إنشاء حساب ولي الأمر
        $parent = User::create([
            'name'     => $request->parent_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'parent',
        ]);

        // ب- إنشاء حساب الطالب وربطه بولي أمره
        $studentEmail = 'kid_' . time() . '@baseera.ai'; // توليد إيميل افتراضي للطالب
        $student = User::create([
            'name'        => $request->child_name,
            'email'       => $studentEmail,
            'password'    => Hash::make($request->password), // لسهولة البدء نضع نفس الباسورد
            'role'        => 'student',
            'parent_id'   => $parent->id,
            'grade_level' => $request->grade,
        ]);

        // جـ- إنشاء ملف تعلم فارغ للذكاء الاصطناعي (Building the AI Brain)
        LearningProfile::create([
            'user_id'       => $student->id,
            'current_level' => 'beginner',
            'strengths'     => [],
            'weaknesses'    => [],
        ]);

        // د- تجهيز سجلات التقدم لجميع المواد (عربي، انجليزي، ماث)
        $subjects = Subject::all();
        foreach ($subjects as $subject) {
            StudentProgress::create([
                'user_id'                 => $student->id,
                'subject_id'              => $subject->id,
                'completion_percentage'   => 0,
            ]);
        }

        // تسجيل دخول الأب تلقائياً بعد التسجيل
        Auth::login($parent);

        return redirect()->to('/parent')->with('success', 'مرحباً بك في عائلة بصيرة! تم إنشاء حسابك وحساب ' . $student->name . ' بنجاح.');
    }

    /**
     * 4. الخروج من النظام
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم تسجيل خروجك بأمان. نراك قريباً!');
    }

    /**
     * وظيفة مساعدة للتوجيه
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin' || $user->role === 'teacher') {
            return redirect()->intended('/admin');
        } elseif ($user->role === 'parent') {
            return redirect()->intended('/parent');
        } elseif ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect('/');
    }
}
