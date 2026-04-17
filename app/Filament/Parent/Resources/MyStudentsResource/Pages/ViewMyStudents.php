<?php

namespace App\Filament\Parent\Resources\MyStudentsResource\Pages;

use App\Filament\Parent\Resources\MyStudentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMyStudents extends ViewRecord
{
    protected static string $resource = MyStudentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // زر العودة لقائمة الأبناء في الأعلى
            Actions\Action::make('back')
                ->label('العودة لقائمة الأبناء')
                ->color('gray')
                ->url(static::$resource::getUrl('index'))
                ->icon('heroicon-m-arrow-right-circle'),
        ];
    }

    /**
     * تخصيص عنوان الصفحة ليظهر اسم الطالب بوضوح
     */
    public function getTitle(): string
    {
        return "الملف الشخصي للابن: " . ($this->record->name ?? '');
    }

    /**
     * يمكنك إضافة ودجات إحصائية تظهر فقط داخل صفحة عرض الطالب
     * مثل مستوى تقدمه في المواد (اختياري)
     */
    protected function getHeaderWidgets(): array
    {
        return [
            // مثلاً: Stats خاص بتركيز هذا الطالب فقط
        ];
    }
}
