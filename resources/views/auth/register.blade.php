@extends('layouts.auth')

@section('title', 'إنشاء حساب ولي أمر جديد')

{{-- تخصيص نص الشريط الجانبي لهذه الصفحة --}}
@section('brand_text', 'لنصنع مساراً تعليمياً فريداً لطفلك يكتشف مهاراته الكامنة من أول فيديو.')

@section('auth_content')
    <!-- الموبايل هيدر -->
    <div class="d-lg-none text-center mb-4 text-right">
        <i class="fa-solid fa-user-plus fs-1 text-navy mb-2"></i>
        <h3 class="fw-black text-navy">حساب ولي أمر جديد</h3>
    </div>

    <div class="d-none d-lg-block mb-4 text-right">
        <h3 class="fw-black text-navy">تسجيل حساب ولي الأمر </h3>
        <p class="text-muted text-sm">أدخل بياناتك، وقم بإعداد الملف الأساسي لطفلك للبدء فوراً.</p>
    </div>

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <!-- القسم الأول: بيانات ولي الأمر -->
        <div class="form-section-title">أولاً: بياناتك الأساسية</div>
        <div class="row">
            <div class="col-md-6">
                <div class="modern-form-group">
                    <i class="fa-regular fa-user form-icon"></i>
                    <input type="text" class="modern-input @error('parent_name') is-invalid @enderror" name="parent_name"
                        placeholder="الاسم الكامل لولي الأمر" required value="{{ old('parent_name') }}" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="modern-form-group">
                    <i class="fa-solid fa-phone form-icon"></i>
                    <input type="text" class="modern-input @error('phone') is-invalid @enderror" name="phone"
                        placeholder="رقم الهاتف للتواصل" required dir="ltr" style="text-align: right"
                        value="{{ old('phone') }}" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="modern-form-group">
                    <i class="fa-regular fa-envelope form-icon"></i>
                    <input type="email" class="modern-input @error('email') is-invalid @enderror" name="email"
                        placeholder="البريد الإلكتروني الخاص بك" required dir="ltr" style="text-align: right"
                        value="{{ old('email') }}" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="modern-form-group">
                    <i class="fa-solid fa-lock form-icon"></i>
                    <input type="password" id="regPassword" class="modern-input pe-5" name="password"
                        placeholder="كلمة المرور" required />
                    <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('regPassword', this)"></i>
                </div>
            </div>
            <div class="col-md-6">
                <div class="modern-form-group">
                    <i class="fa-solid fa-lock form-icon"></i>
                    <input type="password" id="regConfirm" class="modern-input pe-5" name="password_confirmation"
                        placeholder="تأكيد كلمة المرور" required />
                </div>
            </div>
        </div>

        <!-- القسم الثاني: إضافة الطفل -->
        <div class="form-section-title mt-2 text-orange">ثانياً: ملف الطالب التأسيسي</div>
        <div class="row">
            <div class="col-md-12">
                <div class="modern-form-group">
                    <i class="fa-solid fa-child-reaching form-icon" style="color: var(--color-orange)"></i>
                    <input type="text" class="modern-input @error('child_name') is-invalid @enderror" name="child_name"
                        placeholder="الاسم الأول للطفل (أو اسم التدليل)" required value="{{ old('child_name') }}" />
                </div>
            </div>
        </div>

        <!-- إختيار الصف -->
        <label class="d-block mb-3 fw-bold text-navy text-right" style="font-size: 0.95rem">
            أخبرنا بالصف الدراسي للبدء باقتراح المنهج:
        </label>
        <div class="grade-selector">
            @for ($i = 1; $i <= 6; $i++)
                @php
                    $gradeNames = [1 => 'أولى', 2 => 'ثانية', 3 => 'ثالثة', 4 => 'رابعة', 5 => 'خامسة', 6 => 'سادسة'];
                @endphp
                <label class="grade-radio">
                    <input type="radio" name="grade" value="{{ $i }}"
                        {{ old('grade') == $i || $i == 1 ? 'checked' : '' }} />
                    <span class="grade-pill">{{ $gradeNames[$i] }} ابتدائي</span>
                </label>
            @endfor
        </div>

        <!-- الشروط والزر -->
        <div class="form-check mt-3 mb-4 terms-check d-flex text-right">
            <input class="form-check-input ms-2 me-0 border-secondary" type="checkbox" id="termsCheck" required
                style="width: 20px; height: 20px; cursor: pointer" />
            <label class="form-check-label pt-1" for="termsCheck">
                أوافق على قيام "بصيرة" بمعالجة
                <a href="{{ route('conditions') }}" target="_blank">بيانات الكاميرا التفاعلية</a>
                وفقاً لـ <a href="{{ route('conditions') }}" target="_blank">ميثاق الخصوصية</a>.
            </label>
        </div>

        <button type="submit" class="btn-register w-100 shadow-lg">
            إطلاق محرك الذكاء وبناء الملف
            <i class="fa-solid fa-microchip ms-2 text-warning"></i>
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted fw-bold">
            لديك حساب ومُلف لطفلك بالفعل؟
            <a href="{{ route('login') }}" class="text-decoration-none"
                style="color: var(--color-orange); border-bottom: 2px solid rgba(255, 123, 0, 0.4);">
                سجل الدخول من هنا
            </a>
        </p>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(inputId, iconElement) {
            const passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                iconElement.classList.replace('fa-eye', 'fa-eye-slash');
                iconElement.style.color = "var(--color-lightblue)";
            } else {
                passwordInput.type = "password";
                iconElement.classList.replace('fa-eye-slash', 'fa-eye');
                iconElement.style.color = "#94A3B8";
            }
        }
    </script>
@endpush
