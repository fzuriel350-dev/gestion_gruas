<tbody>
    @forelse ($servicios as $s)
    <tr>
        <td><strong>#{{ $s->id }}</strong></td>
        <td>{{ $s->cotizacion?->cliente?->nombre ?: '—' }}</td>
        <td>{{ $s->cotizacion?->folio ?: '—' }}</td>
        <td>{{ $s->operador?->empleado?->nombreCompleto() ?: '—' }}</td>
        <td>{{ $s->unidad?->marca }} {{ $s->unidad?->placas ? '('.$s->unidad->placas.')' : '' }}</td>
        <td>{{ $s->oficina?->nombre ?: '—' }}</td>
        <td>{{ $s->tipoServicio?->nombre ?: '—' }}</td>
        <td>
            <span class="status @switch($s->estado) @case('asignado') status-pending @break @case('inicio_servicio') status-active @break @case('en_sitio_origen') status-active @break @case('en_carga') status-active @break @case('en_transito') status-active @break @case('en_sitio_destino') status-active @break @case('finalizado') status-success @break @case('cancelado') status-danger @break @endswitch">
                <span class="status-dot"></span> {{ str_replace('_', ' ', ucfirst($s->estado)) }}
            </span>
        </td>
        <td>{{ $s->fecha_inicio?->format($fechaHoraFormato) ?: '—' }}</td>
        <td>{{ $s->fecha_fin?->format($fechaHoraFormato) ?: '—' }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('servicios.show', $s) }}" class="btn btn-sm btn-secondary">Ver</a>
                @if ((auth()->user()->isAdmin() || auth()->user()->isCotizador()) && !in_array($s->estado, ['finalizado', 'cancelado']))
                <a href="{{ route('servicios.edit', $s) }}" class="btn btn-sm btn-primary">Editar</a>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="11" class="text-center text-gray-500 py-8">No hay servicios registrados.</td>
    </tr>
    @endforelse
</tbody>
