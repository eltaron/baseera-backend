@extends('layouts.auth')

@section('title', 'تسجيل الدخول')

@section('auth_content')
    <!-- لوجو الموبايل فقط -->
    <div class="d-lg-none text-center mb-4">
        <img src="{{ asset('images/logo.png') }}" width="120" alt="بصيرة" />
    </div>

    <div class="text-center text-lg-start mb-4 text-right">
        <h3 class="fw-black text-navy" style="color: var(--color-navy)">تسجيل الدخول 👋</h3>
        <p class="text-muted">اختر نوع حسابك للوصول إلى لوحة التحكم الخاصة بك.</p>
    </div>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <!-- محدد نوع الحساب (Roles) -->
        <div class="role-selector">
            <label class="role-btn">
                <input type="radio" name="role" value="student" checked />
                <span class="role-text"><i class="fa-solid fa-child me-1 text-orange"></i> طفل / طالب</span>
            </label>
            <label class="role-btn">
                <input type="radio" name="role" value="parent" />
                <span class="role-text"><i class="fa-solid fa-house-user me-1 text-info"></i> ولي أمر</span>
            </label>
            <label class="role-btn">
                <input type="radio" name="role" value="admin" />
                <span class="role-text"><i class="fa-solid fa-user-tie me-1 text-success"></i> مُشرف</span>
            </label>
        </div>

        <!-- الحقول -->
        <div class="modern-form-group">
            <i class="fa-regular fa-envelope form-icon"></i>
            <input type="email" name="email" class="modern-input" placeholder="البريد الإلكتروني المسجل" required
                value="{{ old('email') }}" />
        </div>

        <div class="modern-form-group mb-3">
            <i class="fa-solid fa-lock form-icon"></i>
            <input type="password" id="inputPassword" name="password" class="modern-input pe-5" placeholder="كلمة المرور"
                required />
            <i class="fa-regular fa-eye toggle-password" id="togglePasswordBtn"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 text-sm">
            <label class="d-flex align-items-center text-muted" style="cursor: pointer">
                <input type="checkbox" name="remember" class="form-check-input me-2 ms-0" /> تذكرني دائماً
            </label>
            <a href="{{ route('password.request') }}" class="text-decoration-none fw-bold"
                style="color: var(--color-orange)">نسيت كلمة المرور؟</a>
        </div>

        <button type="submit" class="btn-login mt-2">
            دخول النظام الآمن <i class="fa-solid fa-arrow-left ms-2"></i>
        </button>
    </form>

    <div class="text-center mt-5">
        <p class="text-muted text-sm mb-0">
            ليس لديك حساب على بصيرة؟
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold"
                style="color: var(--color-navy); border-bottom: 2px solid var(--color-orange);">إنشاء حساب جديد</a>
        </p>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById("togglePasswordBtn").addEventListener("click", function() {
            const passwordInput = document.getElementById("inputPassword");
            const icon = this;
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
                icon.style.color = "var(--color-orange)";
            } else {
                passwordInput.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
                icon.style.color = "#94A3B8";
            }
        });
    </script>
@endpush
