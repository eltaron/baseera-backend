<?php

namespace App\Filament\Resources\ParentReportResource\Pages;

use App\Filament\Resources\ParentReportResource;
use App\Filament\Widgets\ReportStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParentReports extends ListRecords
{
    protected static string $resource = ParentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('تقرير جديد لولي أمر'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ReportStats::class,
        ];
    }
}
