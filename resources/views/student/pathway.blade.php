@extends('layouts.student')

@section('title', 'خريطة مغامرة ' . $subject->name)

@section('student_content')
    <header class="content-header mb-5 text-right">
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fa-solid fa-arrow-right ml-2"></i> العودة للمواد
        </a>
        <h1 class="fw-black text-navy">مغامرة <span class="text-orange">{{ $subject->name }}</span> 📐</h1>
        <p class="text-muted">أكمل الدروس لترفع مستوى ذكاء ملفك التعليمي!</p>
    </header>

    {{-- سكشن مراجعة الذكاء الاصطناعي (يظهر إذا كان هناك تنبيه من الـ AI) --}}
    @php
        $needsReview = \App\Models\BehavioralAnalysis::where('user_id', auth()->id())
            ->where('confusion_level', '>', 50)
            ->latest()
            ->first();
    @endphp

    @if ($needsReview)
        <div class="review-card mb-5 text-right p-4 bg-white rounded-4 border-right-danger shadow-sm d-flex align-items-center"
            style="border-right: 5px solid #ef4444;">
            <div class="review-icon bg-danger-light text-danger ms-3"
                style="width:50px; height:50px; display:grid; place-items:center; background:#fee2e2; border-radius:12px;">
                <i class="fa-solid fa-rotate-left fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="fw-bold text-danger mb-1">تحتاج لمراجعة سريعة!</h6>
                <p class="text-muted mb-0 text-sm">بصيرة رصدت "حيرة" مرتفعة في درس
                    <strong>{{ $needsReview->video->title }}</strong>، هل تود تقوية نفسك فيه الآن؟
                </p>
            </div>
            <a href="{{ route('student.view', $needsReview->video->lesson_id) }}"
                class="btn btn-danger btn-sm rounded-pill px-4 me-3">مراجعة الآن</a>
        </div>
    @endif

    <!-- خريطة الدروس الذكية (Pathway Map) -->
    <div class="pathway-container">
        <div class="pathway-line"></div>

        @foreach ($lessonsList as $index => $item)
            {{-- ترتيب الدروس يمين ويسار تلقائياً بناءً على الـ Index --}}
            <div
                class="lesson-step {{ $item['status'] == 'done' ? 'step-done' : ($item['status'] == 'recommended' ? 'step-recommended' : 'step-locked') }}">

                @if ($item['status'] == 'recommended')
                    <span class="ai-label animate__animated animate__pulse animate__infinite">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> ترشيح بصيرة القادم
                    </span>
                @endif

                <div class="lesson-node-wrapper">
                    @if ($item['status'] != 'locked')
                        <a href="{{ route('student.view', $item['id']) }}" class="lesson-node text-decoration-none shadow">
                            @if ($item['status'] == 'done')
                                <i class="fa-solid fa-check text-white"></i>
                            @else
                                <i class="fa-solid fa-play text-orange"></i>
                            @endif
                        </a>
                    @else
                        <div class="lesson-node opacity-50">
                            <i class="fa-solid fa-lock text-muted"></i>
                        </div>
                    @endif
                </div>

                <div class="lesson-info {{ $index % 2 == 0 ? 'info-left' : 'info-right' }}">
                    <h5 class="fw-bold">{{ $item['title'] }}</h5>
                    <p class="small text-muted">
                        @if ($item['status'] == 'done')
                            أتقنت هذه المهارة ✅
                        @elseif($item['status'] == 'recommended')
                            مستوى الصعوبة: {{ $item['difficulty'] }}
                        @else
                            أكمل ما قبله للفتح 🔒
                        @endif
                    </p>
                </div>
            </div>
        @endforeach

    </div>
@endsection
