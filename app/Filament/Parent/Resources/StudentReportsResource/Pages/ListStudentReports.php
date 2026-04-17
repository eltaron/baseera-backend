<?php

namespace App\Filament\Parent\Resources\StudentReportsResource\Pages;

use App\Filament\Parent\Resources\StudentReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentReports extends ListRecords
{
    protected static string $resource = StudentReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
