<div class="p-6 bg-white shadow rounded-lg">
    <h2 class="text-lg font-medium leading-6 text-gray-900">Audiencias de Hoy</h2>

    <!-- Total de audiencias -->
    <p class="mt-1 text-5xl font-bold text-gray-700">
        Total: {{ $totalHearings }}
    </p>

    <!-- Detalle de audiencias -->
    <ul class="mt-4 space-y-2">
        @forelse ($hearings as $hearing)
            <li class="text-gray-600">
                <strong>{{ $hearing->hearing_time }}</strong> -
                {{ $hearing->beneficiary->name ?? 'Desconocido' }} ({{ $hearing->requestType->name ?? 'N/D' }})
            </li>
        @empty
            <p class="text-gray-500">No hay audiencias para hoy.</p>
        @endforelse
    </ul>
</div>
