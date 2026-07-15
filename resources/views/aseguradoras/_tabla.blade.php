<tbody>
    @forelse ($aseguradoras as $a)
    <tr>
        <td class="text-gray-400 text-xs">{{ $a->id }}</td>
        <td><strong>{{ $a->nombre }}</strong></td>
        <td>{{ $a->telefono ?: '—' }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('aseguradoras.show', $a) }}" class="btn btn-sm btn-ghost">Ver</a>
                <a href="{{ route('aseguradoras.edit', $a) }}" class="btn btn-sm btn-primary">Editar</a>
                <form method="POST" action="{{ route('aseguradoras.destroy', $a) }}" data-confirm="¿Eliminar esta aseguradora?">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-secondary">Eliminar</button></form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-center text-gray-500 py-8">No hay aseguradoras registradas.</td>
    </tr>
    @endforelse
</tbody>
