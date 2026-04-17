@extends('layouts.errors')

@section('code', '500')
@section('title', 'ماس تقني')

@section('content')
    <div class="error-container">
        <div class="error-icon text-danger"><i class="fa-solid fa-brain fa-pulse"></i></div>
        <div class="error-code">500</div>
        <h1 class="error-title">حدث "ماس تقني" في عقل بصيرة! 🧠⚡</h1>
        <p class="error-desc">
            يبدو أن خوارزمياتنا قررت أخذ استراحة مفاجئة. المهندسون الآن
            يعملون على إعادة توصيل الأسلاك البرمجية. حاول تحديث الصفحة بعد قليل.
        </p>

        <button onclick="location.reload()" class="btn-retry">
            <i class="fa-solid fa-rotate-right"></i> تحديث الصفحة الآن
        </button>
    </div>
@endsection
