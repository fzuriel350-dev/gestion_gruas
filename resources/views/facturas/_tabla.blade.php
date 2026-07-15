<tbody>
    @forelse ($facturas as $f)
    <tr>
        <td><strong>{{ $f->folio_factura }}</strong></td>
        <td>{{ $f->cliente?->nombre ?? '—' }}</td>
        <td>#{{ $f->servicio?->id }}</td>
        <td>{{ $empresa && $empresa->mostrar_precios ? $moneda . number_format($f->subtotal, 2) : '••••' }}</td>
        <td>{{ $empresa && $empresa->mostrar_precios ? $moneda . number_format($f->iva, 2) : '••••' }}</td>
        <td><strong>{{ $empresa && $empresa->mostrar_precios ? $moneda . number_format($f->total, 2) : '••••' }}</strong></td>
        <td>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
            @if($f->estatus === 'vigente') bg-emerald-100 text-emerald-800
            @else bg-red-100 text-red-800 @endif">
            {{ ucfirst($f->estatus) }}
            </span>
        </td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('facturas.show', $f) }}" class="btn btn-sm btn-ghost">Ver</a>
                @can('admin')<form method="POST" action="{{ route('facturas.destroy', $f) }}" data-confirm="¿Eliminar esta factura?">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-secondary">Eliminar</button></form>@endcan
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center text-gray-500 py-8">No hay facturas registradas.</td>
    </tr>
    @endforelse
</tbody>
