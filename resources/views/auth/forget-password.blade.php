@extends('layouts.app')

@section('title', 'استعادة الوصول إلى الحساب')

{{-- تصفير الهيدر والفوتر في هذه الصفحة فقط لزيادة التركيز (إختياري) --}}
@push('styles')
    <style>
        /* إضافة تنسيقات الصفحة من الكود الأصلي لضمان التمركز */
        body {
            background-color: #F4F7FE !important;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        main#main-content {
            width: 100%;
        }

        /* الأيقونة الأمنية النابضة */
        .security-icon-wrapper::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            border: 1px solid rgba(255, 123, 0, 0.4);
            animation: ripple 2s infinite ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            100% {
                transform: scale(1.3);
                opacity: 0;
            }
        }

        /* حماية من تداخل الهيدر */
        header,
        footer {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

@section('content')
    <!-- الديكورات العائمة للذكاء الاصطناعي -->
    <div class="bg-blob blob-navy"></div>
    <div class="bg-blob blob-orange"></div>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <!-- الكارت الأساسي الاستعادي -->
        <div class="forgot-card shadow-lg p-5 bg-white rounded-4 border-0 text-center" style="max-width: 480px; z-index: 10">

            <!-- الأيقونة الأمنية -->
            <div class="security-icon-wrapper mx-auto mb-4 d-flex align-items-center justify-content-center"
                style="width: 80px; height: 80px; background: #fff4e6; color: var(--color-orange); border-radius: 50%; position: relative; font-size: 2.5rem;">
                <i class="fa-solid fa-unlock-keyhole"></i>
            </div>

            <!-- العنوان والرسالة -->
            <h2 class="fw-black mb-2 text-navy" style="color: var(--color-navy)">استعادة مسار التعلم 🚀</h2>
            <p class="text-muted text-sm mb-4 lh-lg">
                لا تقلق، يحدث ذلك أحياناً! أدخل البريد الإلكتروني المرتبط بحسابك، وسنرسل لك
                <span class="fw-bold" style="color: var(--color-orange)">رابطاً سحرياً ومشفراً</span>
                لإعادة تعيين كلمة المرور فوراً.
            </p>

            <!-- فورم استعادة المرور (نربطها بمسار لارافيل لاحقاً) -->
            <form action="#" method="POST">
                @csrf
                <div class="modern-form-group text-right mb-4">
                    <label class="fw-bold text-navy mb-2 fs-sm d-block">بريدك الإلكتروني المسجل:</label>
                    <div class="position-relative">
                        <i class="fa-regular fa-envelope form-icon"
                            style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                        <input type="email" name="email" class="modern-input w-100 @error('email') is-invalid @enderror"
                            placeholder="email@example.com" required dir="ltr"
                            style="padding: 15px 45px 15px 20px; border-radius: 12px; border: 2px solid #E2E8F0; background: #F8FAFC;"
                            value="{{ old('email') }}">
                    </div>
                </div>

                <button type="submit" class="btn-recover w-100 py-3 border-0 fw-black text-white rounded-3 shadow"
                    style="background: var(--color-navy); font-size: 1.1rem; transition: 0.3s;">
                    إرسال رابط الاستعادة <i class="fa-solid fa-paper-plane ms-2 opacity-75"></i>
                </button>
            </form>

            <!-- العودة للوراء -->
            <div class="mt-4 pt-3">
                <a href="{{ route('login') }}" class="back-to-login text-decoration-none fw-bold" style="color: #64748B;">
                    <i class="fa-solid fa-arrow-right-long me-2"></i> تذكرتها؟ العودة لتسجيل الدخول
                </a>
            </div>
        </div>
    </div>
@endsection
