@extends('layouts.app')

{{-- 1. تخصيص الـ SEO لهذه الصفحة تحديداً لرفع تصنيف جوجل --}}
@section('title', 'الشروط والخصوصية | معايير الأمان في منصة بصيرة')
@section('meta_description',
    'تعرف على التزامنا تجاه خصوصية طفلك وكيف نستخدم الذكاء الاصطناعي لتحليل المشاعر دون تخزين
    بيانات مرئية، وفق معايير GDPR العالمية.')

    @push('styles')
        <style>
            /* تحسينات التصميم للهوية البصرية */
            .policy-header {
                background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
                padding: 80px 0 50px;
                border-bottom: 1px solid #e2e8f0;
            }

            .sidebar-sticky {
                position: sticky;
                top: 100px;
                /* المسافة من التوب بعد الهيدر الثابت */
                background: white;
                border-radius: 15px;
                padding: 20px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            }

            .policy-link {
                display: flex;
                align-items: center;
                padding: 12px 15px;
                color: var(--color-navy);
                text-decoration: none !important;
                border-radius: 10px;
                margin-bottom: 8px;
                transition: 0.3s;
                font-weight: 700;
                font-size: 0.95rem;
            }

            .policy-link i {
                margin-left: 10px;
                font-size: 1.1rem;
                opacity: 0.6;
            }

            .policy-link:hover,
            .policy-link.active {
                color: var(--color-orange);
                background: rgba(255, 123, 0, 0.08);
            }

            .policy-link.active i {
                opacity: 1;
            }

            .content-section {
                margin-bottom: 60px;
                padding-bottom: 40px;
                border-bottom: 1px solid #f1f5f9;
                text-align: right;
            }

            .highlight-box {
                background: rgba(0, 180, 216, 0.05);
                border-right: 5px solid var(--color-lightblue);
                padding: 30px;
                border-radius: 20px;
                margin: 30px 0;
            }

            .content-section h4 {
                font-weight: 900;
                margin-bottom: 25px;
                color: var(--color-navy);
                display: flex;
                align-items: center;
            }

            .content-section h4::before {
                content: '';
                width: 10px;
                height: 10px;
                background: var(--color-orange);
                border-radius: 50%;
                margin-left: 15px;
            }

            .text-sm-date {
                font-size: 0.85rem;
                letter-spacing: 0.5px;
            }
        </style>
    @endpush

@section('content')
    <header class="policy-header text-center">
        <div class="container">
            <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">وثيقة الخصوصية المعتمدة</span>
            <h1 class="fw-black display-5 mb-3" style="color: var(--color-navy)">الخصوصية وشروط الاستخدام</h1>
            <p class="text-muted fs-5 mb-0">آخر تحديث للاتفاقية: <strong>{{ date('M Y') }}</strong></p>
        </div>
    </header>

    <div class="container py-5">
        <div class="row g-5">
            <!-- 2. قائمة التنقل السريع (Sidebar Navigation) -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-sticky border">
                    <h6 class="text-muted fw-bold mb-4 px-3">محتويات الوثيقة:</h6>
                    <a href="#intro" class="policy-link active" onclick="makeActive(this)">
                        <i class="fa-solid fa-shield-halved"></i> مقدمة الأمان
                    </a>
                    <a href="#camera" class="policy-link" onclick="makeActive(this)">
                        <i class="fa-solid fa-video-slash"></i> خصوصية الـ AI
                    </a>
                    <a href="#data" class="policy-link" onclick="makeActive(this)">
                        <i class="fa-solid fa-database"></i> معالجة البيانات
                    </a>
                    <a href="#rights" class="policy-link" onclick="makeActive(this)">
                        <i class="fa-solid fa-scale-balanced"></i> حقوق الأطراف
                    </a>
                </div>
            </div>

            <!-- 3. المحتوى النصي الديناميكي -->
            <div class="col-lg-9">
                <!-- قسم 1 -->
                <section id="intro" class="content-section">
                    <h4>التزام "بصيرة" تجاه طفلك</h4>
                    <p class="fs-5 lh-lg text-secondary">
                        نحن في منصة <strong>بصيرة (Baseera)</strong> نضع أمان الطفل على رأس هرم أولوياتنا التقنية. تم تطوير
                        كافة طبقات النظام وفق معايير <strong>GDPR-K</strong> الصارمة، لضمان بناء بيئة تعلم ذكية تستكشف
                        القدرات دون المساس بقدسية البيانات الشخصية للمنزل.
                    </p>
                </section>

                <!-- قسم 2 (سكشن الكاميرا الهام للمشروع) -->
                <section id="camera" class="content-section">
                    <h4>خصوصية تحليل المشاعر والرؤية الحاسوبية</h4>
                    <p class="fs-5 lh-lg text-secondary">
                        بما أن جوهر مشروعنا هو "بصيرة الذكاء الاصطناعي"، فإننا نؤكد لولي الأمر وللجهات الأكاديمية ما يلي حول
                        آلية عمل الكاميرا:
                    </p>
                    <div class="highlight-box">
                        <ul class="mb-0 list-unstyled lh-lg fs-5">
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle text-orange ms-2"></i>
                                <strong style="color: var(--color-navy)">معالجة EdgeComputing:</strong>
                                النظام يقوم بتحليل ملامح الوجه وتحويلها إلى أرقام هندسية لحظياً "داخل المتصفح" فقط ولا يتم
                                إرسالها لسيرفراتنا.
                            </li>
                            <li class="mb-3">
                                <i class="fa-solid fa-check-circle text-orange ms-2"></i>
                                <strong style="color: var(--color-navy)">صفر تخزين مرئي:</strong>
                                النظام مبرمج برمجياً على <strong>"تجاهل حفظ الإطارات"</strong>؛ لذا لا توجد صورة واحدة أو
                                ثانية فيديو واحدة تُسجل داخل المنصة.
                            </li>
                            <li>
                                <i class="fa-solid fa-check-circle text-orange ms-2"></i>
                                <strong style="color: var(--color-navy)">الشفافية الكاملة:</strong>
                                تعمل الكاميرا فقط بموافقة صريحة من المتصفح وبوجود علامة "التتبع المباشر" أمام الطالب في غرفة
                                المشاهدة.
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- قسم 3 -->
                <section id="data" class="content-section">
                    <h4>ما هي البيانات التي نعالجها برمجياً؟</h4>
                    <p class="fs-5 lh-lg text-secondary">
                        نحن نكتفي فقط بجمع البيانات الإحصائية التي تغذي محرك الـ <strong>Learning Profile</strong> لتحسين
                        مسار الطالب، وهي:
                    </p>
                    <ul class="list-group list-group-flush pr-0 border-0 fs-5 mb-4">
                        <li class="list-group-item bg-transparent border-0 px-0">
                            <i class="fa-solid fa-play-circle text-lightblue ms-2"></i> تفاعلات التشغيل (الإعادة، التوقف،
                            مدة البقاء).
                        </li>
                        <li class="list-group-item bg-transparent border-0 px-0">
                            <i class="fa-solid fa-brain text-lightblue ms-2"></i> متوسط نتائج الأسئلة لتقييم سرعة الاستيعاب.
                        </li>
                        <li class="list-group-item bg-transparent border-0 px-0">
                            <i class="fa-solid fa-chart-line text-lightblue ms-2"></i> النسب المئوية للتركيز والحيرة (بيانات
                            خام مجردة).
                        </li>
                    </ul>
                </section>

                <!-- قسم 4 -->
                <section id="rights" class="content-section border-0">
                    <h4>حقوق ومسؤوليات ولي الأمر</h4>
                    <p class="fs-5 lh-lg text-secondary">
                        لك الحق الأصيل في الدخول لـ <strong>لوحة تحكم ولي الأمر</strong> في أي وقت لمراجعة النتائج
                        الإحصائية. كما توفر لك "بصيرة" حق حذف الحساب نهائياً، وعندها يتم تصفير كافة السجلات المرتبطة
                        بالتحليل السلوكي فوراً وبشكل غير قابل للاسترجاع.
                    </p>
                    <div class="alert alert-light border border-dashed mt-4 text-center">
                        بشرائك أو اشتراكك في المنصة، أنت توافق على استخدام الذكاء الاصطناعي كأداة تطوير تعليمية لطفلك.
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // كود بسيط لتمييز الرابط النشط عند الضغط (UI Improvement)
        function makeActive(el) {
            document.querySelectorAll('.policy-link').forEach(item => item.classList.remove('active'));
            el.classList.add('active');
        }

        // (إختياري) جعل اللينكات نشطة تلقائياً مع السكرول
        window.addEventListener('scroll', function() {
            let sections = ['intro', 'camera', 'data', 'rights'];
            sections.forEach(id => {
                let section = document.getElementById(id);
                let rect = section.getBoundingClientRect();
                if (rect.top >= 0 && rect.top <= 200) {
                    document.querySelectorAll('.policy-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + id) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
@endpush
