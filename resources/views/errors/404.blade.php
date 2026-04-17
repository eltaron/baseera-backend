@extends('layouts.errors')

@section('code', '404')
@section('title', 'تهنا في مسار العلم')

@section('content')
    <div class="bg-elements">
        <i class="fa-solid fa-calculator element" style="top: 10%; right: 15%"></i>
        <i class="fa-solid fa-atom element" style="top: 20%; left: 10%; animation-delay: 1s"></i>
        <i class="fa-solid fa-book-open element" style="bottom: 20%; right: 10%; animation-delay: 2s"></i>
        <i class="fa-solid fa-pencil element" style="bottom: 15%; left: 15%; animation-delay: 1.5s"></i>
        <i class="fa-solid fa-flask element" style="top: 50%; left: 5%; animation-delay: 0.5s"></i>
    </div>
    <div class="error-container">
        <div class="error-icon"><i class="fa-solid fa-compass-drafting fa-spin-slow"></i></div>
        <div class="error-code">404</div>
        <h1 class="error-title">عفواً.. تهنا في مسار العلم! 🚀</h1>
        <p class="error-desc">
            بصيرة قامت بتحليل المسار، ويبدو أن هذه الصفحة قد ضلت طريقها أو تم نقلها لمكان آخر.
            لا تقلق، العودة للمسار الصحيح تبدأ من هنا.
        </p>

        <a href="{{ url('/') }}" class="btn-retry text-decoration-none d-inline-block">
            <i class="fa-solid fa-house"></i> العودة للرئيسية
        </a>
    </div>
@endsection
