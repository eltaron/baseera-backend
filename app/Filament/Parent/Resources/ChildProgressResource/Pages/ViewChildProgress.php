<?php

namespace App\Filament\Parent\Resources\ChildProgressResource\Pages;

use App\Filament\Parent\Resources\ChildProgressResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewChildProgress extends ViewRecord
{
    protected static string $resource = ChildProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('العودة للجدول العام')
                ->color('gray')
                ->url(static::$resource::getUrl('index'))
                ->icon('heroicon-m-chevron-right'),
        ];
    }

    public function getTitle(): string
    {
        return "سجل إنجازات: " . ($this->record->user->name ?? '');
    }

    /**
     * إضافة زر "طباعة التقرير" كمثال لتعزيز إبهار ولي الأمر (تجريبي)
     */
    protected function getHeaderWidgets(): array
    {
        return [
            // يمكنك ربط ودجات إضافية هنا لو أردت ظهور رسم بياني داخل صفحة العرض
        ];
    }
}
