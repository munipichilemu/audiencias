<?php

namespace App\Filament\Resources\HearingResource\Pages;

use App\Filament\Resources\HearingResource;
use Filament\Resources\Pages\ListRecords;

class Summary extends ListRecords
{
    protected static string $resource = HearingResource::class;

    protected static ?string $title = 'Resumen';
    protected static ?string $slug = 'resumen';

    protected function getHeaderWidgets(): array
    {
        return [
//            BeneficiaryHearingStats::class,
        ];
    }
}
