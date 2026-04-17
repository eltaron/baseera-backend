@extends('layouts.app')

{{-- 1. تخصيص الـ SEO لرفع تصنيف الصفحة في محركات البحث --}}
@section('title', 'الدعم الفني والتقني | مركز مساعدة منصة بصيرة')
@section('meta_description',
    'هل لديك استفسارات حول خوارزميات الذكاء الاصطناعي أو خصوصية الكاميرا؟ فريق مهندسي بصيرة
    متاح لمساعدتك على مدار الساعة لضمان أفضل تجربة تعليمية.')

    @push('styles')
        <style>
            /* تحسينات التصميم للهوية البصرية */
            .support-hero {
                background: linear-gradient(135deg, var(--color-navy) 0%, #051a30 100%);
                color: white;
                padding: 100px 0 120px;
                position: relative;
                overflow: hidden;
            }

            .support-hero::after {
                content: "";
                position: absolute;
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(0, 180, 216, 0.15) 0%, transparent 70%);
                border-radius: 50%;
                top: -100px;
                left: -100px;
                filter: blur(50px);
            }

            .status-badge {
                background: rgba(16, 185, 129, 0.1);
                border: 1px solid rgba(16, 185, 129, 0.3);
                color: #10b981;
                padding: 8px 20px;
                border-radius: 50px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 10px;
            }

            .status-dot {
                width: 10px;
                height: 10px;
                background: #10b981;
                border-radius: 50%;
                box-shadow: 0 0 10px #10b981;
                animation: pulse-green 2s infinite;
            }

            @keyframes pulse-green {
                0% {
                    transform: scale(0.9);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.2);
                    opacity: 0.5;
                }

                100% {
                    transform: scale(0.9);
                    opacity: 1;
                }
            }

            .support-card {
                background: white;
                border-radius: 30px;
                border: 1px solid rgba(0, 0, 0, 0.03);
                padding: 45px 35px;
                box-shadow: 0 15px 45px rgba(11, 58, 104, 0.05);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                height: 100%;
                text-align: center;
                position: relative;
                z-index: 5;
            }

            .support-card:hover {
                transform: translateY(-15px);
                box-shadow: 0 30px 60px rgba(11, 58, 104, 0.12);
                border-color: var(--color-orange);
            }

            .icon-circle {
                width: 90px;
                height: 90px;
                background: #f8fafc;
                color: var(--color-lightblue);
                border-radius: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2.5rem;
                margin: 0 auto 30px;
                transition: 0.3s;
            }

            .support-card:hover .icon-circle {
                background: var(--color-navy);
                color: white;
                transform: rotate(-10deg);
            }

            .btn-support {
                background-color: var(--color-orange);
                color: white !important;
                border: none;
                padding: 14px 35px;
                border-radius: 15px;
                font-weight: 800;
                transition: 0.3s;
                box-shadow: 0 8px 20px rgba(255, 123, 0, 0.2);
            }

            .btn-support:hover {
                background-color: var(--color-navy);
                transform: scale(1.05);
            }

            .hero-title {
                font-weight: 900;
                line-height: 1.2;
            }

            label {
                text-align: right
            }
        </style>
    @endpush

@section('content')
    <!-- الهيرو سكشن الفني -->
    <section class="support-hero text-center">
        <div class="container">
            <div class="status-badge mb-4">
                <div class="status-dot"></div>
                <span>الأنظمة والسيرفرات تعمل بكفاءة 100%</span>
            </div>
            <h1 class="display-3 hero-title mb-4">
                مركز الدعم <span class="text-orange">الهندسي والتقني</span>
            </h1>
            <p class="lead opacity-75 mx-auto fs-5" style="max-width: 800px; line-height: 1.8">
                فريق مبرمجي ومهندسي "بصيرة" يعمل على مدار الساعة لضمان استقرار خوارزميات الذكاء الاصطناعي، وتحديث قواعد
                بيانات المناهج، وتوفير أقصى درجات الخصوصية التقنية لبياناتكم.
            </p>
        </div>
    </section>

    <!-- أقسام الدعم التفاعلية -->
    <section class="py-5 position-relative z-2" style="margin-top: -60px;">
        <div class="container">
            <div class="row g-4">
                @foreach ($cards as $card)
                    <div class="col-lg-4 col-md-6">
                        <!-- استخدام لون البوردر من الداتابيز مباشرة -->
                        <div class="support-card border-bottom border-5"
                            style="border-color: {{ $card->border_color }} !important">
                            <div class="icon-circle" style="color: {{ $card->border_color }}">
                                <i class="fa-solid {{ $card->icon }}"></i>
                            </div>
                            <h3 class="fw-bold mb-3" style="color: var(--color-navy)">{{ $card->title }}</h3>
                            <p class="text-secondary mb-5 lh-lg">
                                {{ $card->description }}
                            </p>
                            <a href="{{ $card->button_url }}"
                                class="btn btn-support d-inline-block text-decoration-none shadow"
                                style="background-color: {{ $card->border_color }}">
                                {{ $card->button_text }} <i class="fa-solid fa-arrow-left ms-2"
                                    style="font-size: 0.8rem"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section id="open-ticket" class="py-5" style="background-color: white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-md-4 bg-navy text-white p-5 d-flex flex-column justify-content-center"
                                style="background-color: var(--color-navy)">
                                <i class="fa-solid fa-headset mb-4" style="font-size: 4rem; color: var(--color-orange)"></i>
                                <h3 class="fw-bold mb-3">فتح تذكرة دعم فني</h3>
                                <p class="opacity-75">سيتم الرد على استفسارك وتزويدك برقم تذكرة لمتابعة حالة طلبك برمجياً مع
                                    فريق "بصيرة".</p>
                            </div>
                            <div class="col-md-8 p-5">
                                <form action="{{ route('ticket.store') }}" method="POST">
                                    @csrf
                                    <div class="row g-3 text-right">
                                        <div class="col-md-6 ">
                                            <label class="form-label fw-bold">الاسم</label>
                                            <input type="text" name="name" class="form-control py-3"
                                                placeholder="أدخل اسمك" required>
                                        </div>
                                        <div class="col-md-6 ">
                                            <label class="form-label fw-bold">البريد</label>
                                            <input type="email" name="email" class="form-control py-3"
                                                placeholder="email@example.com" required>
                                        </div>
                                        <div class="col-md-8 ">
                                            <label class="form-label fw-bold">الموضوع / نوع المشكلة</label>
                                            <input type="text" name="subject" class="form-control py-3"
                                                placeholder="مثلاً: مشكلة في تحليل الكاميرا" required>
                                        </div>
                                        <div class="col-md-4 ">
                                            <label class="form-label fw-bold">الأولوية</label>
                                            <select name="priority" class="form-select py-3">
                                                <option value="low">عادية</option>
                                                <option value="medium">متوسطة</option>
                                                <option value="high">عاجلة جداً 🔥</option>
                                            </select>
                                        </div>
                                        <div class="col-12 ">
                                            <label class="form-label fw-bold">شرح تفصيلي</label>
                                            <textarea name="message" class="form-control" rows="4" placeholder="يرجى كتابة تفاصيل المشكلة..." required></textarea>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn btn-orange w-100 py-3 fw-bold shadow-lg">إرسال
                                                البلاغ فوراً</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
