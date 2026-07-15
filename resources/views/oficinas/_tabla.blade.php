<tbody>
    @forelse ($oficinas as $o)
    <tr>
        <td><strong>#{{ $o->id }}</strong></td>
        <td><strong>{{ $o->nombre }}</strong></td>
        <td>{{ $o->direccion ?? '—' }}</td>
        <td>{{ $o->ciudad ?? '—' }}</td>
        <td>{{ $o->estado ?? '—' }}</td>
        <td>{{ $o->telefono ?? '—' }}</td>
        <td>{{ $o->encargado ?? '—' }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('oficinas.show', $o) }}" class="btn btn-sm btn-ghost">Ver</a>
                @can('admin')<a href="{{ route('oficinas.edit', $o) }}" class="btn btn-sm btn-primary">Editar</a>@endcan
                @can('admin')<form method="POST" action="{{ route('oficinas.destroy', $o) }}" data-confirm="¿Eliminar esta oficina?">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-secondary">Eliminar</button></form>@endcan
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center text-gray-500 py-8">No hay oficinas registradas.</td>
    </tr>
    @endforelse
</tbody>
