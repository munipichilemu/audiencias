<?php

namespace App\Filament\Widgets;

use App\Models\Beneficiary;
use App\Models\Hearing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class HearingStats extends BaseWidget
{

    protected function getCards(): array
    {
        // Total de beneficiarios únicos (beneficiary_id distintos)
        $totalBeneficiarios = Beneficiary::query()
            ->select('beneficiary_id') // Seleccionamos los beneficiarios
            ->distinct() // Solo valores únicos
            ->count(); // Contamos los registros únicos

        // Total de audiencias
        $totalAudiencias = Hearing::query()->count();

        // Audiencias por beneficiario (promedio)
        $promedioAudienciasPorBeneficiario = $totalBeneficiarios > 0
            ? round($totalAudiencias / $totalBeneficiarios, 2)
            : 0;

        return [
            BaseWidget\Stat::make('Total Beneficiarios', $totalBeneficiarios)
                ->description('Con audiencias registradas')
                ->color('success'),

            BaseWidget\Stat::make('Total Audiencias', $totalAudiencias)
                ->description('Audiencias totales registradas')
                ->color('primary'),

            BaseWidget\Stat::make('Promedio Audiencias por Beneficiario', $promedioAudienciasPorBeneficiario)
                ->description('Distribución promedio')
                ->color('warning'),
        ];
    }
}
