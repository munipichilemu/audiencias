<?php

namespace App\Exports\Sheets;

use App\Models\Beneficiary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;


class BeneficiariesSheet implements FromCollection, WithHeadings, WithTitle

{
    /**
     * Devuelve la colección de datos de los Beneficiarios.
     */
    public function collection()
    {
        // Recupera los datos y da formato según las columnas en tu tabla
        return Beneficiary::with('sector') // Incluye relación "sector" si es usada en las tablas
            ->get()
            ->map(function ($beneficiary) {
                return [
                    'ID' => $beneficiary->id,
                    'Nombre' => $beneficiary->name,
                    'RUT' => $beneficiary->rut,
                    'Teléfono' => $beneficiary->phone,
                    'Correo Electrónico' => $beneficiary->email,
                    'Ciudad' => $beneficiary->city,
                    'Sector' => $beneficiary->sector ? $beneficiary->sector->name : 'N/A', // Si tienes una relación con "sector"
                ];
            });
    }

    /**
     * Devuelve los encabezados para las columnas.
     */
    public function headings(): array
    {
        return ['ID', 'Nombre', 'RUT', 'Teléfono', 'Correo Electrónico', 'Ciudad', 'Sector'];
    }

    public function title(): string
    {
        return 'Beneficiarios'; // Este será el nombre de la hoja
    }

}
