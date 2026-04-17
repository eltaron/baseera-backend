<script>
    // 1. إعدادات التنبيه الصغير (Toast) للنجاح
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // 2. معالجة رسائل النجاح (Success)
    @if (session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    // 3. معالجة رسائل الأخطاء العامة (Errors)
    @if (session('error') || session('danger'))
        Swal.fire({
            icon: 'error',
            title: 'عفواً.. يوجد خطأ',
            text: "{{ session('error') ?? session('danger') }}",
            confirmButtonText: 'مفهوم',
            confirmButtonColor: '#0B3A68', // لون بصيرة الكحلي
        });
    @endif

    // 4. معالجة أخطاء التحقق من البيانات (Validation Errors)
    @if ($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'بيانات غير مكتملة!',
            html: `
                <div style="text-align: right; direction: rtl;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li style="margin-bottom: 10px;">
                                <i class="fa-solid fa-circle-exclamation text-danger me-2"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            `,
            confirmButtonText: 'تعديل البيانات',
            confirmButtonColor: '#FF7B00', // لون بصيرة البرتقالي
        });
    @endif

    // 5. رسائل المعلومات (Info) - إختياري
    @if (session('info'))
        Toast.fire({
            icon: 'info',
            title: "{{ session('info') }}"
        });
    @endif
</script>
