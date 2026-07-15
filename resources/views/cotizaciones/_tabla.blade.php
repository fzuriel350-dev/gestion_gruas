<tbody>
    @forelse ($cotizaciones as $cot)
    <tr>
        <td><strong>{{ $cot->folio }}</strong></td>
        <td>{{ $cot->cliente?->nombre ?? '—' }}</td>
        <td>{{ $cot->aseguradora?->nombre }}</td>
        <td>{{ $cot->tipoServicio?->nombre }}</td>
        <td>{{ number_format($cot->distancia_km, 1) }} km</td>
        <td>{{ $empresa && $empresa->mostrar_precios ? $moneda . number_format($cot->costo_total, 2) : '••••' }}</td>
        <td>{{ $cot->created_at->format($fechaFormato) }}</td>
        <td>
            <span class="status @switch($cot->estatus) @case('pendiente') status-pending @break @case('aprobado') status-success @break @case('rechazado') status-danger @break @endswitch">
                <span class="status-dot"></span> {{ ucfirst($cot->estatus) }}
            </span>
        </td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('cotizaciones.show', $cot) }}" class="btn btn-sm btn-secondary">Ver</a>
                @if (auth()->user()->isEmpleado() && $cot->estatus === 'pendiente')
                <a href="{{ route('cotizaciones.edit', $cot) }}" class="btn btn-sm btn-primary">Editar</a>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9" class="text-center text-gray-500 py-8">No hay cotizaciones registradas.</td>
    </tr>
    @endforelse
</tbody>
