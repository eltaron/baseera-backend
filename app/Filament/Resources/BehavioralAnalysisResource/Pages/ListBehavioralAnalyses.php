<?php

namespace App\Filament\Resources\BehavioralAnalysisResource\Pages;

use App\Filament\Resources\BehavioralAnalysisResource;
use App\Filament\Widgets\BehavioralStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBehavioralAnalyses extends ListRecords
{
    protected static string $resource = BehavioralAnalysisResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            BehavioralStats::class,
        ];
    }
}
