<?php

namespace App\Exports;

use App\Models\Hearing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BeneficiaryExport implements FromCollection, WithHeadings
{
    protected $beneficiaryId;

    public function __construct($beneficiaryId)
    {
        $this->beneficiaryId = $beneficiaryId;
    }

    public function collection()
    {
        return Hearing::with(['beneficiary', 'requestType'])
            ->where('beneficiary_id', $this->beneficiaryId)
            ->get()
            ->map(function ($hearing) {
                return [
                    'Hora' => $hearing->hearing_time,
                    'Beneficiario' => $hearing->beneficiary->name,
                    'Solicitud' => $hearing->requestType->name,
                    'Notas' => $hearing->notes,
                    'Asistencia' => $hearing->did_assist ? 'Sí' : 'No',
                    'Detalles' => $hearing->details,
                    'Estado de la audiencia' => $hearing->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Hora', 'Beneficiario', 'Solicitud', 'Notas', 'Asistencia', 'Detalles', 'Estado de la audiencia',
        ];
    }

    public function title(): string
    {
        return 'Solicitudes';
    }
}
