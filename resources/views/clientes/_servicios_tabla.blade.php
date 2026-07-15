<tbody>
    @forelse ($servicios as $servicio)
        <tr>
            <td>
                <a href="{{ route('clientes.servicio-show', $servicio) }}" class="table-link">
                    #{{ $servicio->cotizacion->folio ?? 'N/A' }}
                </a>
            </td>
            <td class="text-gray-600">
                {{ $servicio->cotizacion->origen_direccion ?? '-' }} → {{ $servicio->cotizacion->destino_direccion ?? '-' }}
            </td>
            <td>
                <span class="badge badge-gray">{{ $servicio->tipoServicio->nombre ?? $servicio->cotizacion->tipoServicio->nombre ?? '-' }}</span>
            </td>
            <td>
                @php
                    $badges = [
                        'asignado' => 'badge-blue',
                        'inicio_servicio' => 'badge-purple',
                        'en_sitio_origen' => 'badge-purple',
                        'en_carga' => 'badge-purple',
                        'en_transito' => 'badge-indigo',
                        'en_sitio_destino' => 'badge-indigo',
                        'finalizado' => 'badge-green',
                        'cancelado' => 'badge-red',
                    ];
                @endphp
                <span class="badge {{ $badges[$servicio->estado] ?? 'badge-gray' }}">
                    {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                </span>
            </td>
            <td class="text-gray-600">{{ $servicio->operador?->empleado?->nombre ?? '—' }}</td>
            <td class="text-gray-600">{{ $servicio->unidad?->numero_economico ?? '—' }}</td>
            <td class="text-gray-500 text-sm">{{ $servicio->created_at->format($fechaHoraFormato) }}</td>
            <td>
                <a href="{{ route('clientes.servicio-show', $servicio) }}" class="btn-icon" title="Ver detalle">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
            </td>
        </tr>
    @empty
        <tr><td colspan="8" class="text-center py-8 text-gray-500">No hay servicios registrados.</td></tr>
    @endforelse
</tbody>
