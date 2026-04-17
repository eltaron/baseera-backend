<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') | بصيرة Baseera</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />

    <!-- Google Fonts & Bootstrap & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
    @stack('styles')
</head>

<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <div class="login-wrapper row g-0">

                    <!-- الجانب التعريفي (Branding Side) - يظهر في كل صفحات الـ Auth -->
                    <div class="col-lg-5 login-brand-side d-none d-lg-flex">
                        <div class="brand-content">
                            <a href="{{ route('home') }}"
                                class="text-white text-decoration-none d-inline-flex align-items-center mb-5 opacity-75 hover-opacity-100"
                                style="transition: 0.3s">
                                <i class="fa-solid fa-arrow-right me-2"></i> العودة للموقع
                            </a>

                            <img src="{{ asset('images/logo-light.png') }}" width="120" alt="Baseera"
                                class="d-block mb-4" />

                            <p class="fs-5 lh-lg mb-5 opacity-75">
                                @yield('brand_text', 'أول خطوة لطفلك نحو مسار تعليمي مدعوم بالذكاء الاصطناعي، يكتشف نمطه الخاص ويبني مستقبله.')
                            </p>

                            <div class="p-4 rounded-4"
                                style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 40px; height: 40px">
                                        <i class="fa-solid fa-brain"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Learning Profile Engine</h5>
                                </div>
                                <p class="mb-0 text-white-50 fs-sm">نظامنا يحلل مستوى التركيز لحظياً أثناء المشاهدة
                                    لاقتراح أفضل مسار لكل طالب.</p>
                            </div>
                        </div>
                    </div>

                    <!-- مكان محتوى الفورمة المتغير -->
                    <div class="col-lg-7 login-form-side">
                        @yield('auth_content')
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- تشغيل تنبيهات Swal التي برمجناها --}}
    @include('includes.swal-messages')

    @stack('scripts')
</body>

</html>
