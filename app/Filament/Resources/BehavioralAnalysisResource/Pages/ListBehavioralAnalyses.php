<?php

namespace App\Filament\Resources\BehavioralAnalysisResource\Pages;

use App\Filament\Resources\BehavioralAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBehavioralAnalyses extends ListRecords
{
    protected static string $resource = BehavioralAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
