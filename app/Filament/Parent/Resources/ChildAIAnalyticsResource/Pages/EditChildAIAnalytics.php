<?php

namespace App\Filament\Parent\Resources\ChildAIAnalyticsResource\Pages;

use App\Filament\Parent\Resources\ChildAIAnalyticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChildAIAnalytics extends EditRecord
{
    protected static string $resource = ChildAIAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
