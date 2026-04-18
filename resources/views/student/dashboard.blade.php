@extends('layouts.student')

@section('title', 'بوابة الأبطال')

@section('student_content')
    <header class="content-header text-right">
        <div class="greeting">
            <h1>أهلاً بك يا بطل، <span class="text-orange">{{ explode(' ', auth()->user()->name)[0] }}</span> 🚀</h1>
            <p class="text-muted">نظام "بصيرة" حلل تفاعلك السابق وأعد لك رحلة تعليمية مثالية لليوم.</p>
        </div>
    </header>

    <div class="dashboard-grid mt-5 text-right">
        <h4 class="fw-bold mb-4">اختر المادة التي تريد استكشافها:</h4>
        <div class="row g-4">

            {{-- حلقة تكرار لعرض التقدم في المواد الحقيقي من جدول StudentProgress --}}
            @foreach ($subjects_progress as $progress)
                <div class="col-xl-4 col-md-6">
                    <div class="subject-card {{ $progress->subject->name == 'اللغة الإنجليزية' ? 'active-suggested' : '' }}">

                        @if ($progress->subject->name == 'اللغة الإنجليزية')
                            <span class="ai-badge"><i class="fa-solid fa-wand-magic-sparkles"></i> ترشيح بصيرة</span>
                        @endif

                        <div class="card-icon">
                            <i
                                class="fa-solid {{ $progress->subject->name == 'اللغة العربية' ? 'fa-pen-nib' : ($progress->subject->name == 'الرياضيات' ? 'fa-calculator' : 'fa-language') }}"></i>
                        </div>
                        <h3>{{ $progress->subject->name }}</h3>
                        <div class="progress-info">
                            <span>أتممت {{ round($progress->completion_percentage) }}%</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $progress->completion_percentage }}%"></div>
                            </div>
                        </div>
                        <a href="{{ route('student.pathway', $progress->subject_id) }}"
                            class="btn {{ $progress->subject->name == 'اللغة الإنجليزية' ? 'btn-path-orange' : 'btn-path' }} w-100">
                            {{ $progress->subject->name == 'اللغة الإنجليزية' ? 'ابدأ الآن' : 'ادخل المسار' }}
                            <i class="fa-solid fa-arrow-left me-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- صندوق نصيحة الذكاء الاصطناعي (مخرجات من الـ Profile أو التقارير) -->
    <div class="ai-tip-box mt-5 text-right">
        <div class="tip-icon"><i class="fa-solid fa-lightbulb"></i></div>
        <div class="tip-text">
            <h5 class="fw-bold">نصيحة بصيرة لليوم:</h5>
            <p class="mb-0 text-muted">
                لقد لاحظت أنك تبدع في حل تمارين "القسمة"، ما رأيك أن نأخذ تحدياً صغيراً في "الكسور" لنرفع مستوى ملفك
                التعليمي اليوم؟
            </p>
        </div>
    </div>
@endsection
