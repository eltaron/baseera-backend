<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- عنوان الصفحة يتغير ديناميكياً --}}
    <title>@yield('code') | @yield('title') - بصيرة</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />

    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    {{-- ملف التنسيقات الخاص بالأخطاء --}}
    <link rel="stylesheet" href="{{ asset('css/errors.css') }}" />

    <style>
        /* ستايل سريع لضمان التمركز والحيوية */
        :root {
            --navy: #0B3A68;
            --orange: #FF7B00;
            --lightblue: #00B4D8;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .error-container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
            position: relative;
        }

        .error-icon {
            font-size: 8rem;
            color: var(--orange);
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 20px rgba(255, 123, 0, 0.2));
        }

        .error-code {
            font-size: 4rem;
            font-weight: 900;
            color: var(--navy);
            opacity: 0.1;
            line-height: 1;
            margin-bottom: 10px;
        }

        .error-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 15px;
        }

        .error-desc {
            color: #64748B;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 35px;
        }

        .btn-retry {
            background-color: var(--navy);
            color: white;
            padding: 15px 40px;
            border-radius: 15px;
            font-weight: 800;
            border: none;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(11, 58, 104, 0.2);
        }

        .btn-retry:hover {
            background-color: var(--orange);
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>
