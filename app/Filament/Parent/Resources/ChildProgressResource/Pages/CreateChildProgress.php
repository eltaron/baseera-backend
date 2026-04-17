<?php

namespace App\Filament\Parent\Resources\ChildProgressResource\Pages;

use App\Filament\Parent\Resources\ChildProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChildProgress extends CreateRecord
{
    protected static string $resource = ChildProgressResource::class;
}
