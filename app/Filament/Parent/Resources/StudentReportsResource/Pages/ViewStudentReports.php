<?php

namespace App\Filament\Parent\Resources\StudentReportsResource\Pages;

use App\Filament\Parent\Resources\StudentReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentReports extends ViewRecord
{
    protected static string $resource = StudentReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // زر للرجوع لصندوق الرسائل
            Actions\Action::make('back')
                ->label('العودة لصندوق الوارد')
                ->color('gray')
                ->url(static::$resource::getUrl('index'))
                ->icon('heroicon-m-envelope-open'),
        ];
    }

    /**
     * تخصيص عنوان الصفحة ليعرف ولي الأمر عمن يدور التقرير
     */
    public function getTitle(): string
    {
        return "تقرير المعلم بخصوص: " . ($this->record->user->name ?? '');
    }
}
