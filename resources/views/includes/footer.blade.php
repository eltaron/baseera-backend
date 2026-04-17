<!-- ================= الفوتر (Footer) ================= -->
<footer class="modern-footer position-relative mt-0 pt-5">
    <div class="footer-top-glow"></div>
    <div class="footer-bg-pattern"></div>

    <div class="container position-relative z-2">
        <div class="row mb-5 gy-4">
            <div class="col-lg-5 pe-lg-5 mb-4 mb-lg-0 text-right">
                <a href="{{ route('home') }}" class="d-inline-block text-decoration-none mb-4">
                    <h2 class="fw-black mb-0 d-flex align-items-center logo-text" style="color: white">
                        <img src="{{ asset('images/favicon.png') }}" width="60" alt="" class="ms-2" />
                        بصيرة
                    </h2>
                </a>
                <p class="text-white-50 lh-lg fs-6 pe-lg-4 mb-4">
                    منظومة متطورة تجمع بين
                    <strong>التكنولوجيا التربوية</strong> وخوارزميات
                    <strong>تحليل السلوك المرئي</strong>؛ لتحرير التعليم من القيود.
                </p>
            </div>

            <!-- العمود الثاني (روابط المنصة) -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fw-bold mb-4 text-white letter-spacing-custom">روابط سريعة</h5>
                <ul class="list-unstyled footer-links-list">
                    <li><a href="{{ url('/#hero') }}" class="footer-link"><span>المقدمة</span></a></li>
                    <li><a href="{{ url('/#journey') }}" class="footer-link"><span>خوارزميات الـ AI</span></a></li>
                    <li><a href="{{ route('support') }}" class="footer-link"><span>فريق الدعم الهندسي</span></a></li>
                    <li><a href="{{ route('conditions') }}" class="footer-link opacity-75"><span>الشروط
                                والخصوصية</span></a></li>
                </ul>
            </div>

            <!-- العمود الثالث (بوابات السيستم الأهم) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="fw-bold mb-4 text-white letter-spacing-custom">
                    الدخول للنظام
                    <span class="badge bg-warning text-dark rounded-pill fw-bold ms-2" style="font-size: 0.6rem">نظام
                        مُشفر</span>
                </h5>
                <ul class="list-unstyled footer-links-list portals-list">
                    <li>
                        <a href="{{ route('student.dashboard') }}" class="footer-link d-flex align-items-center">
                            <i class="fa-solid fa-user-graduate fs-6 text-lightblue me-2"></i>
                            <span>منصة الطالب (Child Module)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/parent') }}" class="footer-link d-flex align-items-center">
                            <i class="fa-solid fa-house-user fs-6 text-orange me-2"></i>
                            <span>لوحة ولي الأمر (Dashboard)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin') }}" class="footer-link d-flex align-items-center">
                            <i class="fa-solid fa-shield-halved fs-6 text-danger me-2"></i>
                            <span>الإدارة الأكاديمية (CMS Panel)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- الخط السفلي -->
        <div class="footer-bottom-bar row align-items-center py-4 border-top">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 text-white-50 text-sm">
                    &copy; {{ date('Y') }}
                    <strong class="text-white">مشروع بصيرة (Baseera).</strong>
                    جميع الحقوق محفوظة للمشروع.
                </p>
            </div>
            <div class="col-md-6">
                <ul class="social-icons list-unstyled d-flex justify-content-center justify-content-md-end mb-0 gap-3">
                    <li><a href="https://github.com/your-repo" target="_blank"><i class="fa-brands fa-github"></i></a>
                    </li>
                    <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
