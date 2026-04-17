@extends('layouts.errors')

@section('code', '403')
@section('title', 'منطقة محظورة')

@section('content')
    <div class="error-container">
        <div class="error-icon text-primary"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="error-code">403</div>
        <h1 class="error-title">قف.. هذه المنطقة محمية بدروع بصيرة! 🛡️</h1>
        <p class="error-desc">
            أنت لا تملك الصلاحية للدخول هنا. قد يكون حسابك غير مصرح له برؤية هذه البيانات،
            أو أنك تحاول الوصول لملفات الإدارة بطريق الخطأ.
        </p>

        <a href="{{ url('/login') }}" class="btn-retry text-decoration-none d-inline-block">
            <i class="fa-solid fa-lock-open"></i> تسجيل الدخول بحساب مخوّل
        </a>
    </div>
@endsection
