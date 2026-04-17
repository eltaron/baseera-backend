<?php

namespace App\Filament\Resources\SupportCardResource\Pages;

use App\Filament\Resources\SupportCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportCard extends EditRecord
{
    protected static string $resource = SupportCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
