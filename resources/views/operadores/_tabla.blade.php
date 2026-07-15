<tbody>
    @forelse ($operadores as $o)
    <tr>
        <td class="text-gray-400 text-xs">{{ $o->id }}</td>
        <td><strong>{{ $o->empleado?->nombreCompleto() }}</strong></td>
        <td>{{ $o->licencia_tipo }}</td>
        <td>{{ $o->licencia_año_vencimiento?->format($fechaFormato) ?: '—' }}</td>
        <td>{{ $o->licencia_vencimiento_federal?->format($fechaFormato) ?: '—' }}</td>
        <td>{{ $o->puntos_acumulados ?? 0 }}</td>
        <td>{{ $o->unidades->count() }}</td>
        <td>
            @if ($o->disponible)
                <span class="status status-success"><span class="status-dot"></span> Disponible</span>
            @else
                <span class="status status-pending"><span class="status-dot"></span> Ocupado</span>
            @endif
        </td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('operadores.show', $o) }}" class="btn btn-sm btn-ghost">Ver</a>
                <a href="{{ route('operadores.edit', $o) }}" class="btn btn-sm btn-primary">Editar</a>
                <form method="POST" action="{{ route('operadores.destroy', $o) }}" data-confirm="¿Eliminar este operador?">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9" class="text-center text-gray-500 py-8">No hay operadores registrados.</td>
    </tr>
    @endforelse
</tbody>
