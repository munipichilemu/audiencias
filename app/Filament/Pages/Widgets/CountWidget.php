<?php

namespace App\Filament\Pages\Widgets;

use App\Models\Hearing;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CountWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '1s';
    protected function getStats(): array
    {
        return [
            BaseWidget\Stat::make(
                'Audiencias para hoy',
                Hearing::whereDate('hearing_date', Carbon::today())
                    ->count()
            ),
            BaseWidget\Stat::make('Audiencias pendientes por agendar',
                Hearing::query()
                    ->whereNull('hearing_date')
                    ->whereNull('hearing_time')
                    ->count()
            ),
            BaseWidget\Stat::make('Audiencias realizadas durante el año',
                Hearing::query()
                    ->where('did_assist', 1)
                    ->count()
            ),
        ];
    }
}
