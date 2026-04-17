<?php

namespace App\Filament\Resources\SupportCardResource\Pages;

use App\Filament\Resources\SupportCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportCards extends ListRecords
{
    protected static string $resource = SupportCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
