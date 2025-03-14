<?php

namespace App\Http\Controllers;

use App\Models\Hearing; // Asegúrate de tener tu modelo Hearing configurado correctamente.
use Carbon\Carbon; // Utilizado para manejar fechas.

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener la fecha actual
        $today = Carbon::today(); // Fecha de hoy

        // Consulta para filtrar las audiencias de la fecha actual, ordenadas por hora
        $hearings = Hearing::with(['beneficiary', 'requestType']) // Carga relaciones necesarias
            ->whereDate('hearing_date', $today) // Audiencias solo para 'hoy'
            ->orderBy('hearing_time', 'asc') // Ordenamos por hora de la audiencia
            ->get();

        // Contar el total de audiencias del día
        $totalHearings = $hearings->count();

        // Variable enviada a la vista: 'hearings' para audiencias y 'totalHearings' para sumarizadas.
        return view('dashboard', compact('hearings', 'totalHearings'));
    }
}
