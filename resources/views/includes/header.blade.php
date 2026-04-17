<!-- ================= الهيدر والـ Navbar ================= -->
<header>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top glass-header">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="بصيرة" height="60" class="me-2" />
            </a>

            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 mt-3 mt-lg-0 text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#hero') }}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#roles') }}">المستفيدون</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#journey') }}">نظام الذكاء الاصطناعي</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('support') }}">مركز المساعدة</a>
                    </li>
                </ul>

                <div class="d-flex flex-column flex-lg-row gap-2 align-items-center pb-3 pb-lg-0">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-navy fw-bold w-100 px-4"
                            style="min-width: 155px">الدخول للنظام</a>
                        <a href="{{ route('register') }}" class="btn btn-orange fw-bold w-100 shadow-sm">
                            <i class="fa-solid fa-user-plus me-1"></i> ابدأ الآن
                        </a>
                    @else
                        @if (auth()->user()->role == 'student')
                            <a href="{{ route('student.dashboard') }}" class="btn btn-orange fw-bold w-100 shadow-sm">لوحة
                                البطل</a>
                        @elseif(auth()->user()->role == 'parent')
                            <a href="{{ url('/parent') }}" class="btn btn-orange fw-bold w-100 shadow-sm">بوابة ولي
                                الأمر</a>
                        @else
                            <a href="{{ url('/admin') }}" class="btn btn-orange fw-bold w-100 shadow-sm">لوحة التحكم</a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>
