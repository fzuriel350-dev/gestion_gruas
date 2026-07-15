<tbody>
    @forelse ($convenios as $c)
    <tr>
        <td><strong>#{{ $c->id }}</strong></td>
        <td>{{ $c->nombre }}</td>
        <td>{{ $c->aseguradora?->nombre ?: '—' }}</td>
        <td>{{ $c->tipoServicio?->nombre ?: '—' }}</td>
        <td>{{ ucfirst($c->tipo) }}</td>
        <td>{{ $empresa && $empresa->mostrar_precios ? $moneda . number_format($c->costo_banderazo, 2) : '••••' }}</td>
        <td>{{ $empresa && $empresa->mostrar_precios ? $moneda . number_format($c->costo_km, 2) : '••••' }}</td>
        <td>{{ $c->km_incluidos }}</td>
        <td><span class="{{ $c->cubre_casetas_peaje ? 'text-emerald-600' : 'text-red-500' }} font-semibold">{{ $c->cubre_casetas_peaje ? 'Sí' : 'No' }}</span></td>
        <td>{{ $c->descuento }}%</td>
        <td>{{ $c->cobertura }}</td>
        <td>{{ $c->created_at->format($fechaFormato) }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('convenios.show', $c) }}" class="btn btn-sm btn-secondary">Ver</a>
                @if (auth()->user()->isAdmin())
                <a href="{{ route('convenios.edit', $c) }}" class="btn btn-sm btn-primary">Editar</a>
                <form method="POST" action="{{ route('convenios.destroy', $c) }}" class="inline" data-confirm="¿Eliminar este convenio?">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Eliminar</button></form>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="13" class="text-center text-gray-500 py-8">No hay convenios registrados.</td>
    </tr>
    @endforelse
</tbody>
