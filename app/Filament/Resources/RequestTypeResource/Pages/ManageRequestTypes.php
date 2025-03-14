<?php

namespace App\Filament\Resources\RequestTypeResource\Pages;

use App\Filament\Resources\RequestTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRequestTypes extends ManageRecords
{
    protected static string $resource = RequestTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->createAnother(false),
        ];
    }
}
