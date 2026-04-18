<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'لوحة الأبطال') | بصيرة</title>

    <!-- 1. أساسيات الـ SEO الديناميكية -->
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


    <!-- الخطوط والمكتبات الخارجية -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/student-style.css') }}" />

    @stack('styles')
</head>

<body class="student-body">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="student-layout">

        <!-- استدعاء السايدبار كـ Partial -->
        @include('includes.student.sidebar')

        <!-- المحتوى الرئيسي (Main Content) -->
        <main class="main-content">
            <!-- الهيدر الخاص بالموبايل داخل المادة -->
            <header class="mobile-top-bar d-lg-none">
                <button class="toggle-sidebar-btn" id="openSidebar"><i class="fa-solid fa-bars-staggered"></i></button>
                <div class="mobile-logo"><i class="fa-solid fa-eye text-orange me-2"></i><span>بصيرة</span></div>
            </header>

            @yield('student_content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("sidebarOverlay");
        const openBtn = document.getElementById("openSidebar");
        const closeBtn = document.getElementById("closeSidebar");

        openBtn?.addEventListener("click", () => {
            sidebar.classList.add("active");
            overlay.classList.add("active");
        });
        closeBtn?.addEventListener("click", () => {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        });
        overlay?.addEventListener("click", () => {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        });
    </script>
    @stack('scripts')
</body>

</html>
