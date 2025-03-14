<?php

namespace App\Exports\Sheets;

use App\Models\RequestType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RequestTypesSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return RequestType::all()
            ->map(function ($requestType) {
                return [
                    'ID' => $requestType->id,
                    'Nombre' => $requestType->name,
                    'Descripción' => $requestType->description ? $requestType->description : 'Sin descripción', // Muestra "Sin descripción" si no tiene valor
                    //'Color' => $requestType->color ? strtoupper($requestType->color) : 'No configurado', // Convierte el color a mayúsculas si existe

                ];
            });
    }


    public function headings(): array
    {
        return ['ID', 'Nombre', 'Descripción', 'Color'];
    }

    public function title(): string
    {
        return 'Tipo de solicitud'; // Este será el nombre de la hoja
    }
}
