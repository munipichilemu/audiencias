@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Encabezado -->
    <h1 class="text-2xl font-bold mb-4">Panel de Control</h1>

    <!-- Sección de Widgets -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <!-- Widget: Contador de audiencias -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium mb-2">Audiencias del día</h2>
            <p class="text-3xl font-bold text-center text-indigo-500">{{ $totalHearings }}</p>
        </div>
    </div>

    <!-- Tabla de Audiencias -->
    <h2 class="text-lg font-medium mb-4">Audiencias de hoy</h2>

    @if($hearings->isEmpty())
        <p class="text-gray-600">No hay audiencias programadas para el día de hoy.</p>
    @else
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-200 px-4 py-2">Beneficiario</th>
                    <th class="border border-gray-200 px-4 py-2">Hora</th>
                    <th class="border border-gray-200 px-4 py-2">Tipo de Audiencia</th>
                    <th class="border border-gray-200 px-4 py-2">Detalles</th>
                    <th class="border border-gray-200 px-4 py-2">Archivos Adjuntos</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iteramos sobre las audiencias -->
                @foreach($hearings as $hearing)
                    <tr>
                        <!-- Nombre del Beneficiario -->
                        <td class="border border-gray-200 px-4 py-2">
                            {{ $hearing->beneficiary->name ?? 'No disponible' }}
                        </td>

                        <!-- Hora de la Audiencia -->
                        <td class="border border-gray-200 px-4 py-2">
                            {{ $hearing->hearing_time }}
                        </td>

                        <!-- Tipo de Audiencia -->
                        <td class="border border-gray-200 px-4 py-2">
                            {{ $hearing->requestType->type ?? 'No disponible' }}
                        </td>

                        <!-- Detalles -->
                        <td class="border border-gray-200 px-4 py-2">
                            {{ $hearing->details ?? 'Sin detalles' }}
                        </td>

                        <!-- Archivos Adjuntos -->
                        <td class="border border-gray-200 px-4 py-2">
                            @if(!empty($hearing->attachment) && is_array($hearing->attachment))
                                @foreach ($hearing->attachment as $file)
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-blue-500 underline">
                                        Descargar o Ver
                                    </a>
                                    <br />
                                @endforeach
                            @else
                                Sin adjuntos
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
