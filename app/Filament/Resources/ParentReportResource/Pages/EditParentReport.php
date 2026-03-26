<?php

namespace App\Filament\Resources\ParentReportResource\Pages;

use App\Filament\Resources\ParentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParentReport extends EditRecord
{
    protected static string $resource = ParentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
