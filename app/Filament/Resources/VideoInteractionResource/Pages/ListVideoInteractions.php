<?php

namespace App\Filament\Resources\VideoInteractionResource\Pages;

use App\Filament\Resources\VideoInteractionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideoInteractions extends ListRecords
{
    protected static string $resource = VideoInteractionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
