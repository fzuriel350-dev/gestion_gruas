<tbody>
    @forelse ($usuarios as $u)
    <tr>
        <td class="text-gray-400 text-xs">{{ $u->id }}</td>
        <td><strong>{{ $u->name }}</strong></td>
        <td>{{ $u->email }}</td>
        <td>
            <span class="status status-{{ $u->role === 'admin' ? 'active' : ($u->role === 'cliente' ? 'pending' : 'success') }}">
                <span class="status-dot"></span> {{ ucfirst($u->role) }}
            </span>
        </td>
        <td>{{ $u->empleado?->nombreCompleto() ?: '—' }}</td>
        <td class="text-sm text-gray-500">{{ $u->created_at->format($fechaFormato) }}</td>
        <td>
            <div class="flex items-center gap-2">
                <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-primary">Editar</a>
                @if ($u->id !== auth()->id() && $u->wasCreatedBy(auth()->id()))
                <form method="POST" action="{{ route('usuarios.destroy', $u) }}" class="inline"
                    x-data
                    x-on:submit.prevent="Swal.fire({
                        title: '¿Eliminar usuario?',
                        text: 'Se eliminará la cuenta de {{ addslashes($u->name) }}.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(r => { if (r.isConfirmed) $el.submit(); })">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center text-gray-500 py-8">No hay usuarios registrados.</td>
    </tr>
    @endforelse
</tbody>
