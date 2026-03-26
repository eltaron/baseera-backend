<?php

namespace App\Filament\Resources\VideoInteractionResource\Pages;

use App\Filament\Resources\VideoInteractionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoInteraction extends EditRecord
{
    protected static string $resource = VideoInteractionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
