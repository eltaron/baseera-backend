<?php

namespace App\Filament\Parent\Resources\MyStudentsResource\Pages;

use App\Filament\Parent\Resources\MyStudentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyStudents extends EditRecord
{
    protected static string $resource = MyStudentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
