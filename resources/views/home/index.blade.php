@extends('layouts.app')

{{-- تخصيص بيانات الـ SEO للصفحة الرئيسية --}}
@section('title', 'بصيرة | منصة التعليم الذكي والتحليل السلوكي للأطفال')
@section('meta_description',
    'بصيرة هي أول منصة تعليمية عربية تستخدم الذكاء الاصطناعي لتحليل مشاعر طفلك (تركيز، ملل،
    حيرة) وتقديم مسار تعليمي مخصص بناءً على قدراته الفريدة.')

@section('content')
    <main>
        <!-- 1. سكشن الـ Hero (السلوجن) -->
        <section id="hero" class="hero-section text-center text-lg-start">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0 text-right">
                        <div class="badge-ai text-navy mb-4">
                            ✨ إصدار ذكاء اصطناعي V1.0
                        </div>
                        <h1 class="display-3 fw-bold mb-4 line-height-sm">
                            بصيرة.. تعليم ذكي<br />
                            يتكيف مع
                            <span class="slogan-highlight">قدرات طفلك</span>.
                        </h1>
                        <p class="lead mb-5 text-secondary">
                            أول منصة تعليمية تعتمد على الذكاء الاصطناعي ورؤية الحاسوب لتحليل
                            انتباه ومشاعر طفلك أثناء المشاهدة، لتقديم المحتوى الأنسب لنمط
                            تعلمه الخاص.
                        </p>
                        <div class="d-flex justify-content-center justify-content-lg-start gap-3">
                            {{-- إذا كان طفل مسجل يذهب لداشبورد الطالب، وإذا كان زائراً يذهب للتسجيل --}}
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-orange btn-lg px-4 shadow-lg"><i
                                        class="fa-solid fa-rocket me-2"></i> ابدأ الرحلة الآن</a>
                            @else
                                <a href="{{ auth()->user()->role == 'student' ? route('student.dashboard') : url('/parent') }}"
                                    class="btn btn-orange btn-lg px-4 shadow-lg">
                                    <i class="fa-solid fa-gauge-high me-2"></i> العودة للوحة التحكم
                                </a>
                            @endguest

                            <a href="#journey" class="btn btn-outline-navy btn-lg px-4"><i
                                    class="fa-solid fa-play border-0 me-2"></i> شاهد كيف نعمل</a>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="text-center rounded-4 shadow-sm"
                            style="background: rgba(11, 58, 104, 0.05); border: 2px dashed #0b3a68; overflow: hidden;">
                            {{-- استخدام الـ Asset للتأكد من ظهور الصورة --}}
                            <img src="{{ asset('images/hero.jpeg') }}" class="img-fluid" alt="بصيرة - تعليم ذكي" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. سكشن المستفيدون (البوابات الذكية) -->
        <section id="roles" class="py-5 mt-4">
            <div class="container text-center">
                <h2 class="display-5 fw-bold mb-5">بوابات بصيرة الأساسية</h2>
                <div class="row justify-content-center g-5">
                    <!-- بوابة الطالب -->
                    <div class="col-lg-5">
                        <div class="portal-card card-student text-right">
                            <div class="icon-box"><i class="fa-solid fa-gamepad"></i></div>
                            <h3 class="fw-bold mb-3 fs-1">بوابة الطالب الذكية</h3>
                            <p class="fs-5 mb-4 fw-medium text-dark">
                                تجربة مليئة بالتحدي! يتم تعديل مستوى الدروس أوتوماتيكياً حسب مستوى تركيز الطفل المكتشف من
                                الكاميرا.
                            </p>
                            <a href="{{ route('student.dashboard') }}" class="btn btn-modern mt-auto w-100 fs-5">ادخل كبطل
                                <i class="fa-solid fa-arrow-left ms-2"></i></a>
                        </div>
                    </div>

                    <!-- بوابة ولي الأمر -->
                    <div class="col-lg-5">
                        <div class="portal-card card-parent text-right">
                            <div class="icon-box"><i class="fa-solid fa-chart-line"></i></div>
                            <h3 class="fw-bold mb-3 fs-1">لوحة ولي الأمر</h3>
                            <p class="fs-5 mb-4 fw-medium text-dark">
                                راقب أداء ابنك لحظة بلحظة. تقارير مبسطة ترصد مستوى الاستيعاب والتركيز والنقاط التي تحتاج
                                الدعم.
                            </p>
                            <a href="{{ url('/parent') }}" class="btn btn-modern btn-navy mt-auto w-100 fs-5"
                                style="background: var(--color-navy); color: white">لوحة التقارير <i
                                    class="fa-solid fa-arrow-left ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. سكشن رحلة التعليم بالذكاء الاصطناعي (Tech Section) -->
        <section id="journey" class="journey-section mt-5 position-relative overflow-hidden">
            <div class="tech-grid-overlay"></div>
            <div class="container position-relative" style="z-index: 2">
                <div class="text-center mb-5 journey-header">
                    <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill fw-bold tracking-wider">الذكاء
                        الكامن</span>
                    <h2 class="fw-black text-white display-6 mb-3" style="font-weight: 900">
                        كيف يتعلم <span class="text-orange">الذكاء الاصطناعي</span> من طفلك؟
                    </h2>
                    <p class="text-light-50 fs-5 mx-auto" style="max-width: 600px">
                        خوارزميات "بصيرة" تقوم بـ 3 خطوات صامتة لبناء مسار فريد لكل طالب.
                    </p>
                </div>

                <div class="row position-relative g-4 mt-4 step-cards-wrapper">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="step-card h-100 card-step-1">
                            <span class="huge-number">01</span>
                            <div class="icon-circle mb-4 bg-warning-subtle text-warning"><i
                                    class="fa-solid fa-eye fa-xl"></i></div>
                            <h4 class="fw-bold text-white mb-3">تتبع التفاعل السلوكي</h4>
                            <p class="text-light text-sm lh-lg mb-0">تحليل مرات التوقف (Pause)، الإعادة (Replay)، ومدة
                                البقاء داخل الدرس لقياس الرغبة في التعلم.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="step-card h-100 card-step-2">
                            <span class="huge-number">02</span>
                            <div class="icon-circle mb-4 bg-orange-subtle text-orange"
                                style="background: rgba(255, 123, 0, 0.1);"><i
                                    class="fa-solid fa-face-smile-beam fa-xl"></i></div>
                            <h4 class="fw-bold text-white mb-3">رصد المشاعر بالـ AI</h4>
                            <p class="text-light text-sm lh-lg mb-0">مراقبة علامات الحيرة أو الملل أو التركيز الشديد لحظياً
                                دون حفظ أي وسائط مرئية للأمان.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12 mb-4">
                        <div class="step-card h-100 card-step-3">
                            <span class="huge-number text-info">03</span>
                            <div class="icon-circle mb-4 bg-info-subtle text-info"><i
                                    class="fa-solid fa-wand-magic-sparkles fa-xl"></i></div>
                            <h4 class="fw-bold text-white mb-3">تقديم التوصية الذكية</h4>
                            <p class="text-light text-sm lh-lg mb-0">ترشيح الدرس القادم ومستوى الصعوبة المناسب الذي يضمن
                                للطفل الفهم الكامل دون تعقيد.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. الأسئلة الشائعة -->
        <section id="faq" class="faq-section position-relative py-5">
            <div class="container position-relative">
                <div class="text-center mb-5">
                    <span
                        class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold mb-3">نحن
                        هنا للمساعدة</span>
                    <h2 class="fw-black text-navy display-6">الأسئلة الأكثر شيوعاً</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="accordion accordion-flush" id="faqAccordion">
                            @forelse($faqs as $faq)
                                <div class="accordion-item faq-custom-card">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button fw-bold fs-5 collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}">
                                            {{ $faq->question }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            {!! $faq->answer !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted">لا توجد أسئلة شائعة حالياً.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. تواصل معنا (اتصال بـ Laravel Action) -->
        <section id="contact" class="py-5 position-relative overflow-hidden"
            style="background-color: var(--color-bg-light)">
            <!-- ديكورات عائمة لتعزيز الطابع التقني -->
            <div class="blob-orange"></div>
            <div class="blob-blue"></div>

            <div class="container position-relative z-3 my-5">
                <div class="contact-ultra-card shadow-lg mx-auto">
                    <div class="row g-0 align-items-stretch">

                        <!-- النصف الأيمن: معلومات التواصل -->
                        <div class="col-lg-5 contact-info-panel text-white position-relative text-right">
                            <div class="circle-decoration"></div>
                            <div class="position-relative z-2 p-4">
                                <span class="badge text-uppercase tracking-wider mb-3 badge-glow">دعم هندسي 24/7</span>
                                <h2 class="fw-black mb-3 display-6 text-white">لنبنِ مستقبل التعليم <span
                                        class="text-orange">معاً</span></h2>
                                <p class="text-light-50 mb-5 fs-6 lh-lg opacity-75">
                                    لطلب ديمو خاص لمدرستكم أو للاستفسار عن كيفية ربط خوارزميات الذكاء الاصطناعي بمنهج مخصص،
                                    يسعد فريق "بصيرة" الهندسي بالتواصل معكم.
                                </p>

                                <div class="d-flex flex-column gap-4">
                                    <!-- العنصر 1 -->
                                    <a href="mailto:info@baseera.ai"
                                        class="contact-item d-flex align-items-center text-decoration-none">
                                        <div class="icon-box text-lightblue bg-white bg-opacity-10 shadow-sm ps-3">
                                            <i class="fa-solid fa-envelope fs-5"></i>
                                        </div>
                                        <div class="ms-3 me-3 text-right">
                                            <h6 class="text-white-50 mb-0">راسلنا إلكترونياً</h6>
                                            <h5 class="text-white mb-0 fw-bold dir-ltr">info@baseera.ai</h5>
                                        </div>
                                    </a>

                                    <!-- العنصر 2 -->
                                    <a href="#" class="contact-item d-flex align-items-center text-decoration-none">
                                        <div class="icon-box text-success bg-white bg-opacity-10 shadow-sm ps-3">
                                            <i class="fa-brands fa-whatsapp fs-4"></i>
                                        </div>
                                        <div class="ms-3 me-3 text-right">
                                            <h6 class="text-white-50 mb-0">تواصل مباشر (واتساب)</h6>
                                            <h5 class="text-white mb-0 fw-bold dir-ltr">+20 123 456 7890</h5>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- النصف الأيسر: فورم المراسلة -->
                        <div class="col-lg-7 contact-form-panel bg-white p-5 text-right">
                            <h3 class="fw-bold mb-4 text-navy">أرسل استفسارك لفريقنا الفني 🚀</h3>

                            <form action="{{ route('contact.store') }}" method="POST">
                                @csrf {{-- ضروري جداً لتأمين البيانات في لارافيل --}}

                                <div class="row g-3">
                                    <!-- الاسم الكامل -->
                                    <div class="col-md-6">
                                        <div class="modern-input-group">
                                            <i class="fa-regular fa-user text-muted pe-3 icon"></i>
                                            <input type="text" name="name"
                                                class="modern-input @error('name') is-invalid @enderror"
                                                placeholder="اسمك الكريم" required value="{{ old('name') }}" />
                                        </div>
                                    </div>

                                    <!-- البريد الإلكتروني -->
                                    <div class="col-md-6">
                                        <div class="modern-input-group">
                                            <i class="fa-regular fa-envelope text-muted pe-3 icon"></i>
                                            <input type="email" name="email"
                                                class="modern-input @error('email') is-invalid @enderror"
                                                placeholder="بريدك المعتمد" required value="{{ old('email') }}" />
                                        </div>
                                    </div>

                                    <!-- نص الرسالة -->
                                    <div class="col-12 mt-4">
                                        <div class="modern-input-group">
                                            <textarea name="message" class="modern-input py-3 @error('message') is-invalid @enderror" rows="4"
                                                placeholder="كيف يمكننا مساعدتك اليوم؟ اذكر لنا تفاصيل استفسارك..." required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- زر الإرسال المتطور -->
                                    <div class="col-12 mt-4">
                                        <button type="submit"
                                            class="btn btn-submit-modern w-100 d-flex justify-content-center align-items-center gap-2 shadow-lg">
                                            <span>إرسال الطلب الآمن الآن</span>
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                        <div class="text-center mt-3">
                                            <small class="text-muted"><i class="fa-solid fa-lock text-success me-1"></i>
                                                معلوماتك مشفرة ولا تتم مشاركتها مع أي جهات خارجية.</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
