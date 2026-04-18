<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-logo">
            <img src="{{ asset('images/logo-light.png') }}" class="img-fluid" width="40" alt="Logo" />
        </div>
        <button class="close-sidebar-btn d-lg-none" id="closeSidebar"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <!-- البحث الذكي -->
    <div class="sidebar-search">
        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="بحث عن درس..." />
        </div>
    </div>

    <!-- قائمة التنقل -->
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}"><i class="fa-solid fa-house"></i> الرئيسية</a>
            </li>

            <li class="nav-item has-submenu">
                <div class="nav-link-wrapper"><i class="fa-solid fa-book-open"></i><span>موادي الدراسية</span></div>
                <ul class="submenu">
                    {{-- جلب أسماء المواد ديناميكياً من الداتابيز لربطها بصفحاتها --}}
                    @foreach (\App\Models\Subject::all() as $subject)
                        <li class="submenu-item">
                            <a href="{{ route('student.pathway', $subject->id) }}">{{ $subject->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('student.achievements') ? 'active' : '' }}">
                <a href="{{ route('student.achievements') }}"><i class="fa-solid fa-trophy text-warning"></i>
                    إنجازاتي</a>
            </li>
            <li class="nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                <a href="{{ route('student.profile') }}"><i class="fa-solid fa-gear"></i> الإعدادات</a>
            </li>
            <li class="nav-item mt-2">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="logout-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="color: #ff4d4d !important; opacity: 0.8;">
                    <i class="fa-solid fa-power-off text-danger"></i> تسجيل الخروج
                </a>
            </li>
        </ul>
    </nav>

    <!-- فوتر السايدبار (بيانات الطالب الحقيقية) -->
    <div class="sidebar-footer">
        <div class="user-card">
            {{-- توليد الأفاتار بناء على اسم الطالب المسجل حالياً --}}
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FF7B00&color=fff"
                alt="User" />
            <div class="user-info">
                <h6>{{ auth()->user()->name }}</h6>
                <span>الصف {{ auth()->user()->grade_level }} الابتدائي</span>
            </div>
        </div>
    </div>
</aside>
