<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use App\Filament\Widgets\LessonStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLessons extends ListRecords
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    // هــــذا هو الجزء المسؤول عن إظهار الـ Widget في الأعلى
    protected function getHeaderWidgets(): array
    {
        return [
            LessonStats::class,
        ];
    }
}
