@extends('layouts.student')

@section('title', 'إنجازاتي والأوسمة')

@push('styles')
    <style>
        /* تحسينات بصرية إضافية للأوسمة */
        .badge-item.unlocked {
            background: white;
            border: 2px solid var(--orange);
            box-shadow: 0 10px 25px rgba(255, 123, 0, 0.1);
            transform: scale(1.02);
        }

        .badge-item.locked {
            background: #f1f5f9;
            opacity: 0.6;
            border: 2px solid transparent;
            cursor: not-allowed;
        }

        .badge-item.locked i {
            filter: grayscale(1);
            color: #94a3b8;
        }

        .badge-item.legendary {
            border-color: #fbbf24;
            background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);
        }
    </style>
@endpush

@section('student_content')
    <header class="content-header mb-5 text-right">
        <h1 class="fw-black text-navy">لوحة الشرف والأوسمة 🎖️</h1>
        <p class="text-muted">استمر في التعلم يا بطل، فلقد قطعت شوطاً رائعاً في مسارك الدراسي!</p>
    </header>

    <!-- 1. صف الإحصائيات الحقيقية السريع -->
    <div class="row g-4 mb-5 text-right">
        <div class="col-md-4">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm d-flex align-items-center border-0">
                <div class="stat-icon ms-3"
                    style="background: rgba(255, 123, 0, 0.1); color: var(--orange); width: 60px; height: 60px; border-radius: 15px; display: grid; place-items: center;">
                    <i class="fa-solid fa-fire fs-3"></i>
                </div>
                <div class="stat-info">
                    <h3 class="mb-0 fw-black text-navy">{{ $videoCount }}</h3>
                    <p class="mb-0 text-muted small fw-bold">فيديو تعليمي</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm d-flex align-items-center border-0">
                <div class="stat-icon ms-3"
                    style="background: rgba(0, 180, 216, 0.1); color: var(--color-lightblue); width: 60px; height: 60px; border-radius: 15px; display: grid; place-items: center;">
                    <i class="fa-solid fa-star fs-3"></i>
                </div>
                <div class="stat-info">
                    <h3 class="mb-0 fw-black text-navy">{{ number_format($totalPoints) }}</h3>
                    <p class="mb-0 text-muted small fw-bold">نقطة إنجاز</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-4 bg-white rounded-4 shadow-sm d-flex align-items-center border-0">
                <div class="stat-icon ms-3"
                    style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 60px; height: 60px; border-radius: 15px; display: grid; place-items: center;">
                    <i class="fa-solid fa-ranking-star fs-3"></i>
                </div>
                <div class="stat-info">
                    <h3 class="mb-0 fw-black text-navy">#{{ $myRank }}</h3>
                    <p class="mb-0 text-muted small fw-bold">ترتيبك في الصف</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 text-right">
        <!-- 2. قسم الأوسمة الديناميكي -->
        <div class="col-lg-12">
            <h4 class="fw-bold mb-4 text-navy">أوسمتي المحققة</h4>
            <div class="row g-4">

                <!-- وسام المشاهد الشغوف: يُفتح بعد 5 فيديوهات -->
                <div class="col-6 col-md-4">
                    <div class="badge-item {{ $videoCount >= 5 ? 'unlocked' : 'locked' }} text-center p-4 rounded-5">
                        <div class="badge-icon mx-auto mb-3"
                            style="width: 70px; height: 70px; border-radius: 50%; display: grid; place-items: center; background: {{ $videoCount >= 5 ? 'var(--orange)' : '#cbd5e1' }}; color: white; font-size: 1.8rem;">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <h6 class="fw-black text-navy mb-2">المشاهد الشغوف</h6>
                        <p class="small text-muted mb-0">
                            @if ($videoCount >= 5)
                                رائع! أتممت أكثر من 5 فيديوهات
                            @else
                                شاهد {{ $videoCount }}/5 فيديوهات
                            @endif
                        </p>
                    </div>
                </div>

                <!-- وسام عبقري الدقة: يُفتح بعد الحصول على 100% في 3 اختبارات -->
                <div class="col-6 col-md-4">
                    <div
                        class="badge-item {{ $perfectQuizzes >= 3 ? 'unlocked legendary' : 'locked' }} text-center p-4 rounded-5">
                        <div class="badge-icon mx-auto mb-3"
                            style="width: 70px; height: 70px; border-radius: 50%; display: grid; place-items: center; background: {{ $perfectQuizzes >= 3 ? '#fbbf24' : '#cbd5e1' }}; color: white; font-size: 1.8rem;">
                            <i class="fa-solid fa-brain"></i>
                        </div>
                        <h6 class="fw-black text-navy mb-2">عبقري الدقة</h6>
                        <p class="small text-muted mb-0">
                            @if ($perfectQuizzes >= 3)
                                حققت العلامة الكاملة بجدارة
                            @else
                                التفوق 100% ({{ $perfectQuizzes }}/3) مرات
                            @endif
                        </p>
                    </div>
                </div>

                <!-- وسام بطل النقاط: يفتح مثلاً إذا وصل الطالب لـ 2000 نقطة -->
                <div class="col-6 col-md-4">
                    <div class="badge-item {{ $totalPoints >= 2000 ? 'unlocked' : 'locked' }} text-center p-4 rounded-5">
                        <div class="badge-icon mx-auto mb-3"
                            style="width: 70px; height: 70px; border-radius: 50%; display: grid; place-items: center; background: {{ $totalPoints >= 2000 ? 'var(--color-lightblue)' : '#cbd5e1' }}; color: white; font-size: 1.8rem;">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <h6 class="fw-black text-navy mb-2">بطل المسابقات</h6>
                        <p class="small text-muted mb-0">
                            @if ($totalPoints >= 2000)
                                أنت بطل حقيقي ومنافس قوي
                            @else
                                اجمع 2000 نقطة ({{ $totalPoints }}/2000)
                            @endif
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- 3. قائمة المتصدرين الديناميكية -->
        <div class="col-lg-12">
            <h4 class="fw-bold mb-4 text-navy">أوائل الأبطال (صفك)</h4>
            <div class="leaderboard-card bg-white p-3 rounded-5 shadow-sm border border-opacity-50">
                @forelse($topStudents as $index => $topStudent)
                    <div class="rank-item d-flex align-items-center p-3 mb-2 rounded-4
                            {{ $topStudent->id === auth()->id() ? 'active-me' : ($index == 0 ? 'first' : '') }}"
                        style="background: {{ $topStudent->id === auth()->id() ? 'var(--color-navy)' : ($index == 0 ? '#fff9db' : '#f8fafc') }};
                            color: {{ $topStudent->id === auth()->id() ? 'white' : 'var(--color-navy)' }};">

                        <span
                            class="rank-num ms-3 fw-black {{ $topStudent->id === auth()->id() ? 'text-orange' : 'opacity-25' }}">{{ $index + 1 }}</span>

                        <img src="https://ui-avatars.com/api/?name={{ urlencode($topStudent->name) }}&background={{ $index == 0 ? 'fbbf24' : '4f46e5' }}&color=fff"
                            alt="Avatar" class="rounded-circle ms-3 shadow-sm" width="45" height="45">

                        <div class="flex-grow-1">
                            <span class="rank-name fw-bold d-block text-truncate" style="max-width: 140px;">
                                {{ $topStudent->name }} {{ $topStudent->id === auth()->id() ? '(أنت)' : '' }}
                            </span>
                            @if ($index == 0)
                                <small class="text-orange d-block fw-bold"><i class="fa-solid fa-crown me-1"></i> بطل
                                    الصف</small>
                            @endif
                        </div>

                        <span
                            class="rank-points fw-black {{ $topStudent->id === auth()->id() ? 'text-white' : 'text-orange' }}">
                            {{ number_format($topStudent->total_points) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center text-muted">لا توجد منافسة بعد.. كن الأول!</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
