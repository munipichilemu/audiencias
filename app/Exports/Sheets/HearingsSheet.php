<?php

namespace App\Exports\Sheets;

use App\Models\Hearing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class HearingsSheet implements FromCollection, WithHeadings, WithTitle
{
     public function collection()
    {
        // Obtiene audiencias con relaciones
        return Hearing::with(['beneficiary', 'requestType']) // Incluye relaciones
            ->get()
            ->map(function ($hearing) {
                return [
                    'ID' => $hearing->id, // ID de la audiencia
                    'Beneficiario' => $hearing->beneficiary ? $hearing->beneficiary->name : 'N/A', // Nombre del beneficiario
                    'Fecha de Audiencia' => $hearing->hearing_date, // Fecha en formato original
                    'Hora de Audiencia' => $hearing->hearing_time, // Hora en formato original
                    'Tipo de Solicitud' => $hearing->requestType ? $hearing->requestType->name : 'N/A', // Tipo de solicitud
                    'Estado' => ucfirst($hearing->status), // Estado formateado con la primera letra en mayúscula
                ];
            });
    }


    public function headings(): array
    {
        return ['ID', 'ID Beneficiario', 'Fecha de Audiencia', 'Hora de Audiencia', 'Tipo de Solicitud', 'Estado'];
    }

    public function title(): string
    {
        return 'Audiencias'; // Este será el nombre de la hoja
    }
}
