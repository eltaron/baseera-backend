<?php

namespace App\Filament\Resources\StudentProgressResource\Pages;

use App\Filament\Resources\StudentProgressResource;
use App\Filament\Widgets\ProgressStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentProgress extends ListRecords
{
    protected static string $resource = StudentProgressResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ProgressStats::class,
        ];
    }
}
