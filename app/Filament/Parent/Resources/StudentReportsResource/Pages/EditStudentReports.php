<?php

namespace App\Filament\Parent\Resources\StudentReportsResource\Pages;

use App\Filament\Parent\Resources\StudentReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentReports extends EditRecord
{
    protected static string $resource = StudentReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
