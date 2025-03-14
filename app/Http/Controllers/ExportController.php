<?php

namespace App\Http\Controllers;

use App\Exports\FullDataExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportToExcel()
    {
        return Excel::download(new FullDataExport, 'data_completa.xlsx');
    }
}
