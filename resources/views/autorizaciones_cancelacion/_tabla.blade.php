<tbody>
    @forelse ($autorizaciones as $a)
    <tr>
        <td>#{{ $a->servicio?->id }}</td>
        <td>{{ $a->servicio?->cotizacion?->cliente?->nombre ?? '—' }}</td>
        <td>{{ $a->usuarioSolicitante?->name ?? '—' }}</td>
        <td>{{ str_replace('_', ' ', ucfirst($a->tipo_incidencia)) }}</td>
        <td>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
            @if($a->estatus === 'pendiente') bg-yellow-100 text-yellow-800
            @elseif($a->estatus === 'cancelado_por_cotizador') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800 @endif">
            {{ str_replace('_', ' ', ucfirst($a->estatus)) }}
            </span>
        </td>
        <td>{{ $a->fecha_solicitud?->format($fechaHoraFormato) }}</td>
        <td>{{ $a->fecha_resolucion?->format($fechaHoraFormato) ?: '—' }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('autorizaciones-cancelacion.show', $a) }}" class="btn btn-sm btn-ghost">Ver</a>
                @can('admin')<a href="{{ route('autorizaciones-cancelacion.edit', $a) }}" class="btn btn-sm btn-primary">Resolver</a>@endcan
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center text-gray-500 py-8">No hay solicitudes registradas.</td>
    </tr>
    @endforelse
</tbody>
