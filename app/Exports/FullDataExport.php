<?php

namespace App\Exports;

use App\Exports\Sheets\BeneficiariesSheet;
use App\Exports\Sheets\HearingsSheet;
use App\Exports\Sheets\RequestTypesSheet;
use App\Exports\Sheets\SectorsSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FullDataExport implements WithMultipleSheets
{
    /**
     * Devuelve las hojas que tendrá el archivo Excel.
     */
    public function sheets(): array
    {
        return [
            new BeneficiariesSheet,
            new HearingsSheet,
            new RequestTypesSheet,
            new SectorsSheet,
        ];
    }
}
