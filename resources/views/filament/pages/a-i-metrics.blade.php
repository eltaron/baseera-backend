<x-filament-panels::page>
    <!-- القسم العلوي: حالة النظام الذكي (Custom Header) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center space-x-4 space-x-reverse">
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21.48V11.5">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">حالة نموذج الكاميرا</p>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white">يعمل بامتياز</h4>
            </div>
        </div>

        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center space-x-4 space-x-reverse">
            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">سرعة الـ Recommendation</p>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white">120ms</h4>
            </div>
        </div>

        <div
            class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center space-x-4 space-x-reverse">
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">حجم ملفات التعلم</p>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white">بناء 1,240 ملف</h4>
            </div>
        </div>
    </div>

    <!-- هنا سيقوم Filament بحقن الودجات تلقائياً بين الهيدر والفوتر -->

    <!-- قسم نصيحة للمشرف الأكاديمي (Insight Box) -->
    <div class="mt-6 p-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-600 rounded-r-xl">
        <div class="flex items-center space-x-3 space-x-reverse">
            <i class="heroicon-o-light-bulb w-6 h-6 text-blue-600"></i>
            <h3 class="font-bold text-blue-900 dark:text-blue-100">تحليل ذكي للمنصة:</h3>
        </div>
        <p class="mt-2 text-blue-800 dark:text-blue-200">
            بناءً على البيانات الحالية، لوحظ زيادة في "مستوى الحيرة" لطلاب الصف الرابع في وحدة "القسمة". نقترح مراجعة
            الفيديوهات المرتبطة بهذه الوحدة أو تبسيط مستوى الصعوبة.
        </p>
    </div>
</x-filament-panels::page>
