<?php

namespace App\Filament\Resources\LearningProfileResource\Pages;

use App\Filament\Resources\LearningProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLearningProfile extends EditRecord
{
    protected static string $resource = LearningProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
