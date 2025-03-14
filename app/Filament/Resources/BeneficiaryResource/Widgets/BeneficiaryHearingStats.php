<?php

namespace App\Filament\Resources\BeneficiaryResource\Widgets;

use App\Models\Beneficiary;
use App\Models\Hearing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class BeneficiaryHearingStats extends BaseWidget
{
    protected $Beneficiary;
    public $record; // Este será el beneficiario actual
    public Beneficiary $beneficiary;
    public function mount($record)
    {
        $this->beneficiary = $record; // El beneficiario cargado desde la página
    }

    protected function getCards(): array
    {
        // Verifica que $this->record tenga un ID
        if (! $this->beneficiary || ! $this->beneficiary->id) {
            return [];
        }

        // Contamos las audiencias asociadas al beneficio actual
        $totalAudiencias = Hearing::query()
            ->where('beneficiary_id', $this->beneficiary->id) // Filtramos por beneficiario
            ->count();
        // Audiencias asistidas
        $audienciasAsistidas = Hearing::query()
            ->where('beneficiary_id', $this->beneficiary->id)
            ->where('did_assist', true) // Filtramos solo las asistidas
            ->count();

        // Audiencias no asistidas (total - asistidas)
        $audienciasNoAsistidas = $totalAudiencias - $audienciasAsistidas;


        return [
            BaseWidget\Stat::make('Audiencias Registradas', $totalAudiencias)
                ->description('Número total de audiencias para este beneficiario')
                ->color('primary'),
            BaseWidget\Stat::make('Audiencias Asistidas', $audienciasAsistidas)
                ->description('Audiencias a las que asistio')
                ->color('success'),
            BaseWidget\Stat::make('Audiencias No Asistidas', $audienciasNoAsistidas)
            ->description('Audiencias a las que no asistio')
            ->color('danger'),
            BaseWidget\Stat::make('Audiencias rechazadas',  $this->beneficiary->hearings()->where('status', 'rechazado')->count())
            ->description('Audiencias rechazadas')
            ->color('danger'),
        ];
    }
}
