<tbody>
    @forelse ($tipos as $t)
    <tr>
        <td class="text-gray-400 text-xs">{{ $t->id }}</td>
        <td><strong>{{ $t->nombre }}</strong></td>
        <td>{{ $t->descripcion ?: '—' }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('tipos-servicio.show', $t) }}" class="btn btn-sm btn-ghost">Ver</a>
                <a href="{{ route('tipos-servicio.edit', $t) }}" class="btn btn-sm btn-primary">Editar</a>
                <form method="POST" action="{{ route('tipos-servicio.destroy', $t) }}" data-confirm="¿Eliminar este tipo de servicio?">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-secondary">Eliminar</button></form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-center text-gray-500 py-8">No hay tipos de servicio registrados.</td>
    </tr>
    @endforelse
</tbody>
