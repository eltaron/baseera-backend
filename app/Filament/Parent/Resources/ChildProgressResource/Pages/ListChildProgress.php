<?php

namespace App\Filament\Parent\Resources\ChildProgressResource\Pages;

use App\Filament\Parent\Resources\ChildProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChildProgress extends ListRecords
{
    protected static string $resource = ChildProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
