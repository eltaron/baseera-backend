<?php

namespace App\Filament\Parent\Resources\MyStudentsResource\Pages;

use App\Filament\Parent\Resources\MyStudentsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyStudents extends CreateRecord
{
    protected static string $resource = MyStudentsResource::class;
}
