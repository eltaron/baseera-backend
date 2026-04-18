@extends('layouts.app')

@section('title', 'غرفة البصيرة الذكية | ' . $lesson->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/view-room.css') }}" />
    <style>
        /* حالة الزر لما الامتحان يكون خلصان (بدون توهج) */
        .quiz-completed {
            background: #cbd5e1 !important;
            /* لون رمادي هادئ */
            cursor: default !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .quiz-completed:hover {
            transform: none !important;
        }

        .navbar {
            display: none !important;
        }

        @keyframes scan {
            0 % {
                top: 0;
            }

            100 % {
                top: 100 %;
            }
        }

        /* تصميم زر التحدي المكتمل (HUD Completed Style) */
        .quiz-completed {
            background: rgba(16, 185, 129, 0.05) !important;
            /* خلفية خضراء خفيفة جداً شفافة */
            border: 2px solid rgba(16, 185, 129, 0.3) !important;
            /* إطار أخضر شفاف */
            color: #10b981 !important;
            /* تغيير اللون للأخضر الناصح */
            border-radius: 20px !important;
            /* حواف منحنية تطابق الهوية */
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: default !important;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 0 15px rgba(16, 185, 129, 0.05);
            /* ظل داخلي ناعم */
            transition: none !important;
            opacity: 0.9;
        }

        /* وهج خفيف خلف الأيقونة */
        .quiz-completed i {
            filter: drop-shadow(0 0 5px #10b981);
            font-size: 1.2rem;
        }

        /* نص التنبيه السفلي "لقد حللت هذا الاختبار من قبل" */
        .text-success.fw-bold {
            color: #10b981 !important;
            text-shadow: 0 0 8px rgba(16, 185, 129, 0.3);
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }

        /* علامة الكأس */
        .text-success.fw-bold i {
            color: #fbbf24 !important;
            /* لون ذهبي للكأس ليبرز الجائزة */
            filter: drop-shadow(0 0 5px rgba(251, 191, 36, 0.4));
        }
    </style>
@endpush

@section('content')
    @php
        // التأكد إذا كان الطالب قد أدى الاختبار لهذا الفيديو بالفعل
        $isQuizDone = \App\Models\QuizResult::where('user_id', auth()->id())
            ->where('video_id', $lesson->video->id ?? 0)
            ->exists();
    @endphp
    <div class="tech-bg-overlay"></div>
    <div class="tech-grid"></div>

    <!-- ================= هيدر التصفح الذكي ================= -->
    <header class="top-view-header fixed-top">
        <div class="container-fluid px-4 h-100 d-flex justify-content-between align-items-center">
            <div class="header-right">
                <a href="{{ route('student.pathway', $lesson->unit->subject_id) }}"
                    class="back-link d-flex align-items-center text-white-50 text-decoration-none fw-bold">
                    <div style="width: 40px;height: 40px;"
                        class="bg-white bg-opacity-10 p-2 rounded-circle me-3 text-center"><i
                            class="fa-solid fa-arrow-right text-orange"></i></div>
                    <span class="ms-2">العودة للمسار الدراسي</span>
                </a>
            </div>

            <div class="header-center">
                <img src="{{ asset('images/logo-light.png') }}" alt="بصيرة" width="70" class="filter-drop-shadow" />
            </div>

            <div class="header-left">
                <div
                    class="bg-opacity-5 p-1 px-3 rounded-pill border border-white border-opacity-10 d-flex align-items-center">
                    <span class="text-white-50 small me-3">البطل الحالي:</span>
                    <span class="text-white fw-bold small ms-2">{{ auth()->user()->name }}</span>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FF7B00&color=fff"
                        class="rounded-circle ms-2" width="30" height="30">
                </div>
            </div>
        </div>
    </header>

    <main class="container-fluid py-5 mt-5">
        <div class="row g-4 px-md-4">

            <!-- الجانب الأيمن: عنوان الدرس ومؤشر الحيرة (مساحة ترحيبية) -->
            <div class="col-12 mb-2 d-flex justify-content-between align-items-start text-right">
                <div>
                    <span class="badge bg-orange mb-2">الوحدة {{ $lesson->unit->id }} - {{ $lesson->unit->title }}</span>
                    <h1 class="display-6 fw-black text-white m-0">الآن: <span
                            class="text-orange">{{ $lesson->title }}</span></h1>
                </div>

            </div>

            <!-- 1. المحتوى المرئي (المساحة الكبرى) -->
            <div class="col-lg-8">
                <div class="video-glow-frame">
                    <div class="video-player-wrapper position-relative overflow-hidden rounded-4"
                        style="aspect-ratio: 16/9; background: #000;">
                        @if ($lesson->video && $lesson->video->video_url)
                            <iframe width="100%" height="100%"
                                src="{{ str_replace('watch?v=', 'embed/', $lesson->video->video_url) }}?autoplay=1&rel=0"
                                frameborder="0" allowfullscreen class="rounded-4">
                            </iframe>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                <i class="fa-solid fa-video-slash text-white-50 fs-1 mb-3"></i>
                                <p class="text-white-50">لم يتم ربط فيديو بهذا الدرس حتى الآن</p>
                            </div>
                        @endif

                        <!-- إشعار الخصوصية HUD style -->
                        <div class="position-absolute p-2 bg-dark bg-opacity-70 text-white-50"
                            style="bottom: 15px; left: 15px; border-radius: 10px; font-size: 9px; letter-spacing: 1px;">
                            <i class="fa-solid fa-shield-halved text-success me-1"></i> AES-256 ENCRYPTED | NO CLOUD
                            RECORDING
                        </div>
                    </div>
                </div>

                <!-- مساحة أسفل الفيديو (الوصف) لملء الفراغ -->
                <div class="mt-4 p-4  bg-opacity-5 rounded-4 border border-white border-opacity-5 text-right text-white-50">
                    <h6 class="text-white fw-bold"><i class="fa-solid fa-circle-info me-2 text-lightblue"></i> أهدافك في هذا
                        الدرس:</h6>
                    <p class="small mb-0">استمع جيداً لمفهوم "{{ $lesson->video->skill ?? 'المحتوى الأكاديمي' }}"، لاحظ
                        جيداً التحركات البصرية. بنهاية المقطع ستظهر لك بصيرة بعض الأسئلة الممتعة لتتحدى بها نفسك وتجمع
                        المزيد من نقاط الذكاء!</p>
                </div>
            </div>

            <!-- 2. مراقب بصيرة (HUD AI Monitor) -->
            <div class="col-lg-4">
                <div class="hud-panel p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4 text-right">
                        <h5 class="fw-black text-white m-0 small"><i class="fa-solid fa-fingerprint text-orange me-2"></i>
                            تتبع الشخصية الرقمية</h5>
                        <div class="ai-label-neon">AI Scan</div>
                    </div>

                    <!-- معاينة الكاميرا التقنية -->
                    <div class="camera-wrap position-relative rounded-4 overflow-hidden mb-4 border border-info border-opacity-25"
                        style="aspect-ratio: 4/3; background: #010811;">
                        <div style="top: 40%;right: 40%;"
                            class="position-absolute inset-0 opacity-20 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-user-gear fs-1 text-white"></i>
                        </div>
                        <div class="scanning-line position-absolute w-100"
                            style="height: 2px; background: linear-gradient(90deg, transparent, #00B4D8, transparent); animation: scan 3s linear infinite;">
                        </div>
                        <div class="position-absolute top-0 right-0 m-3 px-2 py-1 bg-danger rounded-1 text-white fw-black"
                            style="font-size: 8px;">SIGNAL: STRONG</div>

                        <!-- إطار التركيز البصري الوهمي -->
                        <div class="position-absolute"
                            style="top:25%; left:25%; width:50%; height:50%; border:1px dashed rgba(0, 180, 216, 0.4); border-radius:15px;">
                        </div>
                    </div>

                    <!-- إحصائيات بصرية (تحليلات حية) -->
                    <div class="stats-wrapper text-right">
                        <p class="text-white-50 small mb-4 fw-bold">السمات السلوكية الحالية:</p>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2 small text-white">
                                <span>قوة التركيز (Concentration)</span>
                                <span class="text-success fw-bold">89%</span>
                            </div>
                            <div class="progress"
                                style="height: 12px; background: rgba(255,255,255,0.05); border-radius: 6px; padding: 2px;">
                                <div class="progress-bar bg-success rounded-3" style="width: 89%;"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2 small text-white">
                                <span>استجابة الفهم (Logic Check)</span>
                                <span class="text-warning fw-bold">أخضر متصل</span>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1 bg-success opacity-75" style="height: 4px; border-radius: 2px;">
                                </div>
                                <div class="flex-grow-1 bg-success opacity-75" style="height: 4px; border-radius: 2px;">
                                </div>
                                <div class="flex-grow-1 bg-success opacity-75" style="height: 4px; border-radius: 2px;">
                                </div>
                                <div class="flex-grow-1 bg-success opacity-25" style="height: 4px; border-radius: 2px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- التوصية اللحظية بطريقة الرسائل التقنية -->
                    <div class="mt-auto">
                        <div class="bg-indigo bg-opacity-10 p-3 rounded-4 border-right border-indigo mb-4 shadow-sm"
                            style="border-right: 4px solid var(--color-lightblue); background: rgba(0, 180, 216, 0.05);">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-comment-medical text-light me-2"></i>
                                <span class="text-white small fw-black">بصيرة تخبرك:</span>
                            </div>
                            <p class="mb-0 text-white-50 small leading-relaxed">لاحظت بصيرة ثبات نظراتك في الجزء السابق، هذا
                                مذهل! استمر بهذه الدقة فلقد قاربت على إنهاء المسار بتميز.</p>
                        </div>

                        <!-- زر الانهاء الصارخ (Glow Button) -->
                        <button id="btn-record-watch" onclick="markAsWatched({{ $lesson->video->id }})"
                            class="btn btn-outline-info w-100 py-2 mb-2 rounded-pill small"
                            style="border-style: dashed; font-size: 12px;">
                            <i class="fa-solid fa-check-circle ms-1"></i> اضغط هنا لإنهاء قسم المشاهدة
                        </button>

                        @if (!$isQuizDone)
                            <!-- زر الامتحان يظهر بحالته الطبيعية (ينبض) -->
                            <a href="{{ route('student.quiz', $lesson->id) }}"
                                class="finish-lesson-btn btn w-100 py-3 text-white fw-black shadow-lg">
                                <i class="fa-solid fa-bolt-lightning ms-2 text-warning"></i> أنا جاهز للتحدي.. اختبرني
                            </a>
                        @else
                            <!-- زر الامتحان في حالة الإتمام (مطفي وغير مفعل) -->
                            <div class="quiz-completed btn w-100 py-3 text-white-50 fw-bold">
                                <i class="fa-solid fa-circle-check ms-2 text-success"></i> أتممت التحدي بنجاح
                            </div>
                            <p class="text-center small mt-2 text-success fw-bold">لقد حللت هذا الاختبار من قبل 🏆</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function markAsWatched(videoId) {
            fetch("{{ route('video.complete') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        video_id: videoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'عمل رائع يا بطل',
                            text: 'تم تسجيل تقدمك بنجاح في هذا المقطع.',
                            confirmButtonText: 'ممتاز',
                            confirmButtonColor: '#FF7B00'
                        });
                        document.getElementById('btn-record-watch').innerHTML =
                            '<i class="fa-solid fa-check-double ms-1"></i> تمت المشاهدة';
                        document.getElementById('btn-record-watch').classList.replace('btn-outline-info',
                            'btn-success');
                        document.getElementById('btn-record-watch').disabled = true;
                    }
                });
        }
    </script>
    <script>
        console.log("الذكاء الاصطناعي يستكشف مهارات البطل {{ auth()->user()->name }}");
    </script>
@endpush
