<?php

namespace App\Filament\Resources\LearningProfileResource\Pages;

use App\Filament\Resources\LearningProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLearningProfiles extends ListRecords
{
    protected static string $resource = LearningProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
