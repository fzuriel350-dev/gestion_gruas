<tbody>
    @forelse ($cotizaciones as $cotizacion)
        <tr>
            <td>
                <span class="font-mono font-semibold text-sm">#{{ $cotizacion->folio }}</span>
            </td>
            <td class="text-gray-600 text-sm">
                {{ $cotizacion->origen_direccion }} → {{ $cotizacion->destino_direccion }}
            </td>
            <td>
                <span class="badge badge-gray">{{ $cotizacion->tipoServicio->nombre ?? '-' }}</span>
            </td>
            <td class="text-sm text-gray-600">
                {{ $cotizacion->marca ?? '—' }} {{ $cotizacion->modelo ?? '' }}
            </td>
            <td class="font-semibold text-sm">
                {{ $empresa && $empresa->mostrar_precios ? $moneda . number_format($cotizacion->costo_total, 2) : '••••' }}
            </td>
            <td>
                @php
                    $badges = [
                        'pendiente' => 'badge-yellow',
                        'aprobado' => 'badge-green',
                        'rechazado' => 'badge-red',
                    ];
                @endphp
                <span class="badge {{ $badges[$cotizacion->estatus] ?? 'badge-gray' }}">
                    {{ ucfirst($cotizacion->estatus) }}
                </span>
            </td>
            <td class="text-gray-500 text-sm">{{ $cotizacion->created_at->format($fechaFormato) }}</td>
            <td>
                <div class="flex items-center gap-1 justify-end">
                    <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="btn-icon" title="Ver detalle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                    @if ($cotizacion->estatus === 'pendiente')
                        <form method="POST" action="{{ route('clientes.cotizaciones.aprobar', $cotizacion) }}" class="inline" data-confirm-title="✅ Aprobar cotización" data-confirm="Al aprobar esta cotización se generará un servicio de grúa automáticamente. ¿Deseas continuar?" data-confirm-icon="success" data-confirm-button="Sí, aprobar">
                            @csrf
                            <button type="submit" class="btn-icon text-emerald-600 hover:bg-emerald-50" title="Aprobar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                        <button type="button" class="btn-icon text-red-500 hover:bg-red-50" title="Rechazar" onclick="rechazarCotizacion('{{ $cotizacion->folio }}', {{ $cotizacion->id }})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="8" class="text-center py-8 text-gray-500">No hay cotizaciones registradas.</td></tr>
    @endforelse
</tbody>
