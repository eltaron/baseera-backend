<?php

namespace App\Filament\Parent\Resources\ChildAIAnalyticsResource\Pages;

use App\Filament\Parent\Resources\ChildAIAnalyticsResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewChildAIAnalytics extends ViewRecord
{
    protected static string $resource = ChildAIAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // إضافة زر للعودة لقائمة التقارير في الأعلى
            Actions\Action::make('back')
                ->label('العودة للتقارير')
                ->color('gray')
                ->url(static::$resource::getUrl('index'))
                ->icon('heroicon-m-arrow-right-circle'),
        ];
    }

    /**
     * تخصيص ترويسة الصفحة لتظهر بشكل شخصي للأب
     */
    public function getTitle(): string
    {
        return "تحليل بصيرة للابن: " . ($this->record->user->name ?? '');
    }
}
