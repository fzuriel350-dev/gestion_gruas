<?php

namespace App\Http\Controllers;

use App\Models\AutorizacionCancelacion;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Empresa;
use App\Models\Servicio;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ClientePanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isCliente()) {
                abort(403);
            }
            return $next($request);
        });
    }

    protected function clienteId(): ?int
    {
        return Cliente::where('usuario_id', auth()->id())->value('id');
    }

    public function servicios(Request $request)
    {
        $empresaId = session('empresa_id');
        $clienteId = $this->clienteId();

        if (!$clienteId) {
            $servicios = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            return Inertia::render('Clientes/Servicios', ['servicios' => $servicios]);
        }

        $query = Servicio::where('empresa_id', $empresaId)
            ->whereHas('cotizacion', fn($q) => $q->where('cliente_id', $clienteId))
            ->with('cotizacion.tipoServicio', 'operador.empleado', 'unidad', 'tipoServicio');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('cotizacion', fn($qq) => $qq->where('folio', 'like', "%{$q}%"));
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $servicios = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Clientes/Servicios', ['servicios' => $servicios]);
    }

    public function servicioShow(Servicio $servicio)
    {
        if ($servicio->cotizacion->cliente_id !== $this->clienteId()) {
            abort(403);
        }

        $servicio->load(
            'cotizacion.cliente',
            'cotizacion.tipoServicio',
            'operador.empleado',
            'unidad',
            'tipoServicio'
        );

        $progreso = match ($servicio->estado) {
            'asignado' => 2,
            'inicio_servicio', 'en_sitio_origen', 'en_carga' => 3,
            'en_transito', 'en_sitio_destino' => 4,
            'finalizado' => 5,
            'cancelado' => 0,
            default => 1,
        };

        return Inertia::render('Clientes/ServicioShow', ['servicio' => $servicio, 'progreso' => $progreso]);
    }

    public function cancelarServicio(Request $request, Servicio $servicio)
    {
        if ($servicio->cotizacion->cliente_id !== $this->clienteId()) {
            abort(403);
        }

        if (in_array($servicio->estado, ['finalizado', 'cancelado'])) {
            return back()->with('error', 'El servicio ya está finalizado o cancelado.');
        }

        $request->validate([
            'motivo' => 'required|string|max:1000',
        ]);

        AutorizacionCancelacion::create([
            'empresa_id' => session('empresa_id'),
            'servicio_id' => $servicio->id,
            'usuario_solicitante_id' => auth()->id(),
            'motivo_cancelacion' => $request->motivo,
            'tipo_incidencia' => 'cliente_cancela',
            'estatus' => 'pendiente',
            'fecha_solicitud' => now(),
        ]);

        $notificacionesEnabled = Empresa::find(session('empresa_id'))?->notificaciones_habilitadas ?? true;

        $empleados = User::where('empresa_id', session('empresa_id'))
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($empleados as $emp) {
            if ($notificacionesEnabled) {
                Notificacion::create([
                    'empresa_id' => session('empresa_id'),
                    'usuario_id' => $emp->id,
                    'mensaje' => "El cliente solicitó cancelar el servicio #{$servicio->id}. Motivo: {$request->motivo}.",
                    'tipo' => 'servicio',
                    'estado' => 'no_leida',
                ]);
            }
            Cache::forget("notificaciones_no_leidas_{$emp->id}");
        }

        return redirect()->route('clientes.servicio-show', $servicio)
            ->with('success', 'Solicitud de cancelación enviada. Un administrador la revisará pronto.');
    }

    public function notificaciones()
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $notificaciones = Notificacion::where('empresa_id', $empresaId)
            ->where('usuario_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Clientes/Notificaciones', ['notificaciones' => $notificaciones]);
    }

    public function notificacionLeer(Notificacion $notificacione)
    {
        $notificacione->update(['estado' => 'leida']);
        Cache::forget("notificaciones_no_leidas_" . auth()->id());
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function notificacionesLeerTodas()
    {
        $user = auth()->user();
        Notificacion::where('empresa_id', session('empresa_id'))
            ->where('usuario_id', $user->id)
            ->where('estado', 'no_leida')
            ->update(['estado' => 'leida']);
        Cache::forget("notificaciones_no_leidas_" . $user->id);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function aprobarCotizacion(Request $request, Cotizacion $cotizacione)
    {
        $user = auth()->user();
        if ($cotizacione->cliente_id !== $this->clienteId() || $cotizacione->estatus !== 'pendiente') {
            abort(403);
        }

        $cotizacione->update(['estatus' => 'aprobado']);

        $servicio = Servicio::create([
            'empresa_id' => $cotizacione->empresa_id,
            'cotizacion_id' => $cotizacione->id,
            'tipo_servicio_id' => $cotizacione->tipo_servicio_id,
            'estado' => 'asignado',
            'descripcion' => 'Servicio generado por aprobación de cotización.',
        ]);

        $notificacionesEnabled = Empresa::find(session('empresa_id'))?->notificaciones_habilitadas ?? true;

        if ($notificacionesEnabled) {
            Notificacion::create([
                'empresa_id' => $cotizacione->empresa_id,
                'usuario_id' => $user->id,
                'mensaje' => "Cotización {$cotizacione->folio} aprobada. Servicio generado.",
                'tipo' => 'cotizacion_aprobada',
                'estado' => 'no_leida',
            ]);
        }

        $empleados = User::where('empresa_id', $cotizacione->empresa_id)
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($empleados as $emp) {
            if ($notificacionesEnabled) {
                Notificacion::create([
                    'empresa_id' => $cotizacione->empresa_id,
                    'usuario_id' => $emp->id,
                    'mensaje' => "El cliente aprobó la cotización {$cotizacione->folio}. Se generó un servicio.",
                    'tipo' => 'cotizacion_aprobada',
                    'estado' => 'no_leida',
                ]);
            }
            Cache::forget("notificaciones_no_leidas_{$emp->id}");
        }

        return redirect()->route('clientes.servicios')
            ->with('success', 'Cotización aprobada. Servicio generado correctamente.');
    }

    public function rechazarCotizacion(Request $request, Cotizacion $cotizacione)
    {
        if ($cotizacione->cliente_id !== $this->clienteId() || $cotizacione->estatus !== 'pendiente') {
            abort(403);
        }

        $request->validate(['motivo' => 'nullable|string|max:500']);

        $user = auth()->user();

        $cotizacione->update(['estatus' => 'rechazado']);

        $notificacionesEnabled = Empresa::find(session('empresa_id'))?->notificaciones_habilitadas ?? true;

        if ($notificacionesEnabled) {
            Notificacion::create([
                'empresa_id' => $cotizacione->empresa_id,
                'usuario_id' => $user->id,
                'mensaje' => "Cotización {$cotizacione->folio} rechazada.",
                'tipo' => 'cotizacion_rechazada',
                'estado' => 'no_leida',
            ]);
        }

        $empleados = User::where('empresa_id', $cotizacione->empresa_id)
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        foreach ($empleados as $emp) {
            if ($notificacionesEnabled) {
                Notificacion::create([
                    'empresa_id' => $cotizacione->empresa_id,
                    'usuario_id' => $emp->id,
                    'mensaje' => "El cliente rechazó la cotización {$cotizacione->folio}.",
                    'tipo' => 'cotizacion_rechazada',
                    'estado' => 'no_leida',
                ]);
            }
            Cache::forget("notificaciones_no_leidas_{$emp->id}");
        }

        return redirect()->route('clientes.cotizaciones')
            ->with('success', 'Cotización rechazada.');
    }

    public function cotizaciones(Request $request)
    {
        $empresaId = session('empresa_id');
        $clienteId = $this->clienteId();

        $query = Cotizacion::where('empresa_id', $empresaId)
            ->where('cliente_id', $clienteId)
            ->with('aseguradora', 'tipoServicio');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qq) use ($q) {
                $qq->where('folio', 'like', "%{$q}%")
                    ->orWhere('origen_direccion', 'like', "%{$q}%")
                    ->orWhere('destino_direccion', 'like', "%{$q}%");
            });
        }
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        $cotizaciones = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Clientes/Cotizaciones', ['cotizaciones' => $cotizaciones]);
    }

    public function perfil()
    {
        $user = auth()->user()->load('cliente.aseguradora');
        $cliente = $user->cliente;
        $stats = [];

        if ($cliente) {
            $stats = [
                'total_cotizaciones' => Cotizacion::where('cliente_id', $cliente->id)->count(),
                'total_servicios' => Servicio::whereHas('cotizacion', fn($q) => $q->where('cliente_id', $cliente->id))->count(),
                'servicios_activos' => Servicio::whereHas('cotizacion', fn($q) => $q->where('cliente_id', $cliente->id))
                    ->whereIn('estado', Servicio::ESTADOS_ACTIVOS)
                    ->count(),
            ];
        }

        return Inertia::render('Clientes/Perfil', [
            'user' => $user,
            'cliente' => $cliente,
            'stats' => $stats,
        ]);
    }

    public function updatePerfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()->mixedCase()->numbers()->symbols()->uncompromised()],
            'telefono' => 'required|string|max:20',
            'numero_poliza' => 'required|string|max:50',
            'tipo_cobertura_poliza' => 'required|string|max:100|regex:/^[\p{L}\s]+$/u',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'calle' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'num_exterior' => 'nullable|string|max:20|regex:/^[\p{L}\p{N}\s]*$/u',
            'num_interior' => 'nullable|string|max:20|regex:/^[\p{L}\p{N}\s]*$/u',
            'colonia' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'codigo_postal' => 'required|string|max:10|regex:/^[\d]+$/',
            'localidad' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'municipio' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'estado' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
        ]);

        $data = $request->only(['name']);

        if ($request->hasFile('foto_perfil')) {
            if ($user->foto_perfil) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
            $data['foto_perfil'] = $request->file('foto_perfil')->store('fotos_perfil', 'public');
        }

        $user->update($data);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $cliente = $user->cliente;
        if ($cliente) {
            $cliente->update($request->only(['telefono', 'numero_poliza', 'tipo_cobertura_poliza', 'calle', 'num_exterior', 'num_interior', 'colonia', 'codigo_postal', 'localidad', 'municipio', 'estado']));
        }

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
