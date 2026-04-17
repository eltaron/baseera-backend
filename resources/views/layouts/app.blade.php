<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- 1. أساسيات الـ SEO الديناميكية -->
    <title>@yield('title', 'بصيرة | منصة التعليم الذكية المدعومة بالذكاء الاصطناعي')</title>
    <meta name="description" content="@yield('meta_description', 'منصة بصيرة: تجربة تعليمية فريدة للأطفال تعتمد على تحليل السلوك والمشاعر عبر الكاميرا لتخصيص المحتوى التعليمي لحظياً.')" />
    <meta name="keywords"
        content="تعليم أطفال، ذكاء اصطناعي، رؤية حاسوبية، منصة بصيرة، Baseera AI، تعليم تفاعلي، مدرسة المستقبل" />
    <meta name="author" content="Baseera Team">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- 2. بطاقات التواصل الاجتماعي (Open Graph / Facebook / LinkedIn) -->
    <meta property="og:site_name" content="بصيرة" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title', 'بصيرة | منصة التعليم الذكية')" />
    <meta property="og:description" content="@yield('meta_description', 'بصيرة تستخدم الذكاء الاصطناعي لبناء مسار تعليمي مخصص لطفلك بناءً على انتباهه وحيرته أمام الدروس.')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="@yield('og_image', asset('images/og-main-cover.jpg'))" />

    <!-- 3. بطاقات تويتر (Twitter Cards) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'بصيرة | منصة التعليم الذكية')">
    <meta name="twitter:description" content="@yield('meta_description', 'تعليم ذكي يتكيف مع قدرات طفلك لحظياً عبر الذكاء الاصطناعي.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-main-cover.jpg'))">

    <!-- 4. الأيقونات والسمة -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />
    <meta name="theme-color" content="#0B3A68" />

    <!-- 5. الخطوط والمكتبات (Assets) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet" />

    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <!-- SweetAlert2 CSS (اختياري لتحسين السرعة) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">


    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @stack('styles')
</head>

<body>
    @include('includes.header')

    @yield('content')

    @include('includes.footer')

    <!-- الـ Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // 1. تنبيه النجاح (Success Session)
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // 2. تنبيه الفشل أو الخطأ (Error Session)
        @if (session('error') || session('danger'))
            Swal.fire({
                icon: 'error',
                title: 'عفواً!',
                text: "{{ session('error') ?? session('danger') }}",
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#0B3A68', // لون بصيرة الكحلي
            });
        @endif

        // 3. تنبيه أخطاء المدخلات (Validation Errors)
        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'يوجد خطأ في البيانات!',
                html: '<ul style="text-align: right; list-style: none;">' +
                    @foreach ($errors->all() as $error)
                        '<li><i class="fa-solid fa-circle-exclamation me-2"></i> {{ $error }}</li>' +
                    @endforeach
                '</ul>',
                confirmButtonText: 'تعديل البيانات',
                confirmButtonColor: '#FF7B00', // لون بصيرة البرتقالي
            });
        @endif
    </script>
    @stack('scripts')
</body>

</html>
