<?php

namespace App\Exports\Sheets;

use App\Models\Sector;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SectorsSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return Sector::select('id', 'name', 'description')
            ->get()
            ->map(function ($sector) {
                return [
                    'ID' => $sector->id, // Muestra el ID
                    'Nombre' => $sector->name, // Nombre del sector
                    'Descripción' => $sector->description ? $sector->description : 'Sin descripción', // Descripción o texto por defecto
                ];
            });
    }


    public function headings(): array
    {
        return ['ID', 'Nombre', 'Descripción'];
    }

    public function title(): string
    {
        return 'Sectores'; // Este será el nombre de la hoja
    }
}
