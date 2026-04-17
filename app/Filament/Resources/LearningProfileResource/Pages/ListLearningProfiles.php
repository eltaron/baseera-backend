<?php

namespace App\Filament\Resources;

namespace App\Filament\Resources\LearningProfileResource\Pages;

use App\Filament\Resources\LearningProfileResource;
use App\Filament\Widgets\ProfileStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLearningProfiles extends ListRecords
{
    protected static string $resource = LearningProfileResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ProfileStats::class,
        ];
    }
}
