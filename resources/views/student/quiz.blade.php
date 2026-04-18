@extends('layouts.app')

@section('title', 'تحدي بصيرة الذكي | ' . $lesson->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}" />
    <style>
        /* إخفاء العناصر المشتتة لزيادة تركيز الطفل في الاختبار */
        header,
        footer {
            display: none !important;
        }

        body {
            background-color: #030a13 !important;
        }

        .timeout-screen,
        .result-screen {
            text-align: center;
            display: none;
            padding: 20px 0;
        }

        .timeout-icon {
            font-size: 5rem;
            color: var(--color-orange);
            margin-bottom: 20px;
            animation: shake 0.5s infinite;
        }

        @keyframes shake {

            0%,
            100% {
                transform: rotate(0);
            }

            25% {
                transform: rotate(5deg);
            }

            75% {
                transform: rotate(-5deg);
            }
        }

        .correct-bg {
            background: rgba(16, 185, 129, 0.1) !important;
            border-color: #10b981 !important;
            color: #065f46 !important;
        }

        .wrong-bg {
            background: rgba(239, 68, 68, 0.1) !important;
            border-color: #ef4444 !important;
            color: #991b1b !important;
        }
    </style>
@endpush

@section('content')
    <div class="quiz-overlay" id="quizOverlay">
        <div class="quiz-card shadow-2xl rounded-5 p-4 p-md-5" id="quizCard">
            <!-- شريط الوقت التفاعلي -->
            <div class="quiz-timer-wrapper">
                <div class="timer-progress" id="timerBar" style="width: 100%; transition: width 1s linear;"></div>
            </div>

            <!-- 1. واجهة السؤال (تحتوي على بيانات ديناميكية من JS) -->
            <div id="questionView">
                <div class="quiz-header d-flex justify-content-between align-items-center mb-4">
                    <span class="question-number badge bg-light text-navy p-2 px-3 rounded-pill fw-bold"
                        id="questionCounter">
                        سؤال 1 من ..
                    </span>
                    <div class="text-orange d-flex align-items-center gap-2">
                        <span class="timer-text fs-4 fw-black" id="timerText">30</span>
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <div class="question-text fs-3 fw-bold text-navy mb-4 text-right" id="questionContent">
                    جاري تحميل التحدي من بصيرة...
                </div>

                <div class="options-list d-grid gap-3" id="optionsList">
                    <!-- الخيارات سيتم ضخها من المصفوفة تحت -->
                </div>
            </div>

            <!-- 2. واجهة النتيجة (Success View) -->
            <div class="result-screen" id="resultView">
                <div class="result-icon text-warning mb-3">
                    <i class="fa-solid fa-trophy" style="font-size: 6rem;"></i>
                </div>
                <h2 class="fw-black text-navy mb-3">عاش يا بطل! ذكاء خارق 🌟</h2>
                <p class="text-muted fs-5 mb-4" id="successDetails"></p>
                <div class="p-3 bg-light rounded-4 mb-4">
                    <p class="mb-0 text-navy fw-bold small"><i class="fa-solid fa-wand-magic-sparkles text-orange me-2"></i>
                        تم تحديث ملفك التعليمي بمهارات جديدة.</p>
                </div>
                <a href="{{ route('student.dashboard') }}"
                    class="btn btn-orange px-5 py-3 rounded-pill fw-bold text-white shadow-lg text-decoration-none">
                    العودة للرئيسية <i class="fa-solid fa-arrow-left ms-2"></i>
                </a>
            </div>

            <!-- 3. واجهة انتهاء الوقت (Timeout View) -->
            <div class="timeout-screen" id="timeoutView">
                <div class="timeout-icon"><i class="fa-solid fa-hourglass-end"></i></div>
                <h2 class="fw-black text-navy mb-3">للأسف.. انتهى الوقت! ⏳</h2>
                <p class="text-muted lh-lg">لقد كنت قريباً جداً! التفكير العميق رائع، لكن "بصيرة" تحب أن ترى سرعتك أيضاً
                    لترشيح الأنسب لك.</p>
                <div class="mt-4">
                    <button onclick="location.reload()" class="btn btn-navy px-4 py-2 rounded-pill me-2">حاول
                        مجدداً</button>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill">تخطي
                        الآن</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @php
        $mappedQuestions = $questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question' => $q->question_text,
                'options' => $q->options,
                'correct' => $q->correct_answer,
            ];
        });
    @endphp
    <script>
        // جلب البيانات الحقيقية من PHP وتحويلها لمصفوفة JS
        const quizData = @json($mappedQuestions);


        let currentQuestion = 0;
        let timeLeft = 30; // 30 ثانية لكل سؤال
        const totalTime = 30;
        let timerId;
        let score = 0;
        let totalTimeTaken = 0;

        function startQuiz() {
            if (quizData.length === 0) {
                alert('عفواً، لا توجد أسئلة لهذا الدرس حالياً.');
                window.history.back();
                return;
            }
            renderQuestion();
            startTimer();
        }

        function renderQuestion() {
            const q = quizData[currentQuestion];
            document.getElementById("questionCounter").innerText = `سؤال ${currentQuestion + 1} من ${quizData.length}`;
            document.getElementById("questionContent").innerText = q.question;

            const optionsContainer = document.getElementById("optionsList");
            optionsContainer.innerHTML = "";

            // الخيارات هي نصوص بسيطة في المصفوفة
            q.options.forEach((opt) => {
                const btn = document.createElement("div");
                btn.className = "option-btn p-3 border-2 rounded-4 fw-bold text-right cursor-pointer shadow-sm";
                btn.style.cursor = "pointer";
                btn.innerHTML = `<i class="fa-solid fa-circle-dot me-3 text-muted"></i> ${opt}`;
                btn.onclick = () => handleAnswer(opt, btn);
                optionsContainer.appendChild(btn);
            });

            // ريست للتايمر لكل سؤال
            timeLeft = totalTime;
            updateTimerUI();
        }

        function handleAnswer(selectedText, btnElement) {
            const q = quizData[currentQuestion];
            const allBtns = document.querySelectorAll('.option-btn');
            allBtns.forEach(b => b.style.pointerEvents = 'none');

            // تحديث إجمالي الوقت المستغرق (للـ AI Analysis)
            totalTimeTaken += (totalTime - timeLeft);

            if (selectedText === q.correct) {
                btnElement.classList.add("correct-bg");
                btnElement.innerHTML = `<i class="fa-solid fa-circle-check ms-3"></i> مذهل! إجابة صحيحة`;
                score++;

                setTimeout(() => {
                    currentQuestion++;
                    if (currentQuestion < quizData.length) {
                        renderQuestion();
                        allBtns.forEach(b => b.style.pointerEvents = 'auto');
                    } else {
                        finishQuiz();
                    }
                }, 1200);
            } else {
                btnElement.classList.add("wrong-bg");
                btnElement.innerHTML = `<i class="fa-solid fa-circle-xmark ms-3"></i> حاول في السؤال القادم يا بطل`;

                setTimeout(() => {
                    currentQuestion++;
                    if (currentQuestion < quizData.length) {
                        renderQuestion();
                        allBtns.forEach(b => b.style.pointerEvents = 'auto');
                    } else {
                        finishQuiz();
                    }
                }, 1500);
            }
        }

        function startTimer() {
            timerId = setInterval(() => {
                timeLeft--;
                updateTimerUI();

                if (timeLeft <= 0) {
                    clearInterval(timerId);
                    showTimeout();
                }
            }, 1000);
        }

        function updateTimerUI() {
            document.getElementById("timerText").innerText = timeLeft;
            const percentage = (timeLeft / totalTime) * 100;
            const timerBar = document.getElementById("timerBar");
            timerBar.style.width = percentage + "%";

            // تلوين التايمر لو الوقت قرب يخلص
            if (timeLeft < 10) timerBar.style.backgroundColor = "#ef4444";
            else timerBar.style.backgroundColor = "var(--color-orange)";
        }

        function finishQuiz() {
            clearInterval(timerId);

            const accuracy = Math.round((score / quizData.length) * 100);
            const avgTime = totalTimeTaken / quizData.length;

            // --- إرسال البيانات للسيرفر عبر AJAX ---
            fetch("{{ route('student.quiz.submit') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        video_id: "{{ $lesson->video->id }}",
                        lesson_id: "{{ $lesson->id }}",
                        accuracy: accuracy,
                        avg_time: avgTime
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // إظهار واجهة النجاح فقط بعد التأكد من حفظ البيانات
                        document.getElementById("questionView").style.display = "none";
                        document.getElementById("resultView").style.display = "block";
                        document.getElementById("successDetails").innerText =
                            `لقد أجبت على ${score} من أصل ${quizData.length} بدقة ${accuracy}%!`;

                        console.log("AI Database Updated ✅");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حفظ تقدمك، يرجى المحاولة لاحقاً.');
                });
        }

        window.onload = startQuiz;
    </script>
@endpush
