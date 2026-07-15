<tbody>
    @forelse ($clientes as $c)
    <tr>
        <td class="text-gray-400 text-xs">{{ $c->id }}</td>
        <td><strong>{{ $c->nombre }}</strong></td>
        <td>{{ $c->empresa ?? '—' }}</td>
        <td>{{ $c->telefono ?? '—' }}</td>
        <td>{{ $c->email ?? '—' }}</td>
        <td>{{ $c->aseguradora?->nombre ?? '—' }}</td>
        <td class="text-xs">{{ $c->numero_poliza ? $c->numero_poliza . ' — ' . ($c->tipo_cobertura_poliza ?? '') : '—' }}</td>
        <td>{{ $c->contacto ?? '—' }}</td>
        <td class="max-w-[160px] truncate">{{ $c->direccion ?? '—' }}</td>
        <td>{{ $c->servicios_count }}</td>
        <td>{{ $c->ultimo_servicio ? $c->ultimo_servicio->format($fechaFormato) : '—' }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('clientes.edit', $c) }}" class="btn btn-sm btn-primary">Editar</a>
                <a href="{{ route('clientes.show', $c) }}" class="btn btn-sm btn-secondary">Historial</a>
                <form method="POST" action="{{ route('clientes.destroy', $c) }}" data-confirm="¿Eliminar este cliente?">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="12" class="text-center text-gray-500 py-8">No hay clientes registrados.</td>
    </tr>
    @endforelse
</tbody>
