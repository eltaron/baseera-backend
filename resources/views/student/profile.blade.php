@extends('layouts.student')

@section('title', 'ملفي الشخصي')

@section('student_content')
    <!-- هيدر البروفايل (Hero Section) -->
    <div class="profile-header-card mb-5 text-right">
        <div class="profile-cover"></div>
        <div class="profile-info-wrap">
            <div class="profile-avatar-edit">
                {{-- توليد الصورة بناء على اسم الطالب الحالي --}}
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=FF7B00&color=fff"
                    id="profileImg" alt="Avatar">
                {{-- <label for="avatarUpload" class="edit-icon"><i class="fa-solid fa-camera"></i></label>
                <input type="file" id="avatarUpload" hidden disabled> --}}
            </div>
            <div class="profile-text">
                <h2 class="fw-black mb-1 text-white">{{ $user->name }}</h2>
                <p class="mb-0 text-white-50">
                    <i class="fa-solid fa-graduation-cap me-1"></i> بطل في الصف {{ $user->grade_level }} الابتدائي
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 text-right">
        <!-- قسم تعديل البيانات -->
        <div class="col-lg-8">
            <div class="settings-card bg-white shadow-sm p-4 rounded-4">
                <h4 class="fw-bold mb-4 text-navy">
                    <i class="fa-solid fa-address-card ms-2 text-orange"></i> تعديل بياناتي
                </h4>

                <form action="{{ route('student.profile.update') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted">الاسم بالكامل</label>
                            <div class="input-group-modern">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" name="name"
                                    class="form-control-modern @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}">
                            </div>
                        </div>

                        <div class="col-md-12 mt-4">
                            <h6 class="fw-bold text-navy border-bottom pb-2 mb-3">تغيير كلمة المرور الأمان</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">كلمة المرور الحالية</label>
                            <div class="input-group-modern">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" name="current_password" class="form-control-modern"
                                    placeholder="••••••••">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">كلمة المرور الجديدة</label>
                            <div class="input-group-modern">
                                <i class="fa-solid fa-key"></i>
                                <input type="password" name="new_password" class="form-control-modern"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-orange px-5 py-3 rounded-3 shadow">
                                حفظ التغييرات <i class="fa-solid fa-check-double ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- قسم "بصيرة" للذكاء الاصطناعي (AI Insight) -->
        <div class="col-lg-4">
            <div class="ai-identity-card p-4 rounded-4 shadow-sm" style="background: #021124; color: white;">
                <h5 class="fw-bold text-white mb-4"><i class="fa-solid fa-wand-magic-sparkles ms-2 text-orange"></i> هويتي
                    التعليمية</h5>

                @if ($user->learningProfile)
                    <div class="identity-badge mb-4 p-3 rounded-3"
                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="id-icon text-orange fs-2"><i class="fa-solid fa-eye"></i></div>
                            <div class="id-text">
                                <h6 class="mb-0 text-white">
                                    {{ $user->learningProfile->preferred_learning_style ?? 'بانتظار التحليل' }}</h6>
                                <p class="mb-0 text-muted small">نمط التعلم المستنتج</p>
                            </div>
                        </div>
                    </div>

                    <div class="identity-stats space-y-3">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-white-50">قوة الاستيعاب</span>
                                <span class="small">85%</span>
                            </div>
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1)">
                                <div class="progress-bar bg-info" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-white-50">التركيز الذهني</span>
                                <span class="small">70%</span>
                            </div>
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1)">
                                <div class="progress-bar bg-warning" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="ai-comment mt-4 p-3 rounded-3"
                        style="background: rgba(255, 123, 0, 0.1); font-size: 0.85rem; border-right: 3px solid var(--color-orange);">
                        <p class="mb-0 text-light">بصيرة لاحظت أنك تبدع في الدروس التي تعتمد على المشاهدة البصرية أكثر من
                            القراءة الجافة.</p>
                    </div>
                @else
                    <p class="text-white-50 text-center">بصيرة بدأت في تحليل بياناتك.. استمر في الدراسة لتظهر نتائج هويتك
                        هنا!</p>
                @endif
            </div>
        </div>
    </div>
@endsection
