<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Empresa;
use App\Models\Convenio;
use App\Models\Cliente;
use App\Models\Aseguradora;
use App\Models\Notificacion;
use App\Models\TipoServicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CotizacionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $query = Cotizacion::where('empresa_id', $empresaId)
            ->with('cliente', 'aseguradora', 'tipoServicio');

        if ($user->isCliente()) {
            $query->where('usuario_creador_id', $user->id);
        }

        if (request('aseguradora_id')) {
            $query->where('aseguradora_id', request('aseguradora_id'));
        }

        if (request('tipo_servicio_id')) {
            $query->where('tipo_servicio_id', request('tipo_servicio_id'));
        }

        if (request('estatus')) {
            $query->where('estatus', request('estatus'));
        }

        if ($desde = request('desde')) {
            $query->whereDate('created_at', '>=', $desde);
        }

        if ($hasta = request('hasta')) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('folio', 'like', "%{$q}%")
                    ->orWhere('origen_direccion', 'like', "%{$q}%")
                    ->orWhere('destino_direccion', 'like', "%{$q}%")
                    ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
            });
        }

        $orden = request('orden', 'creacion_desc');
        switch ($orden) {
            case 'cliente_asc':
                $query->orderBy(Cliente::select('nombre')->whereColumn('clientes.id', 'cotizaciones.cliente_id'));
                break;
            case 'cliente_desc':
                $query->orderByDesc(Cliente::select('nombre')->whereColumn('clientes.id', 'cotizaciones.cliente_id'));
                break;
            case 'total_asc':
                $query->orderBy('costo_total');
                break;
            case 'total_desc':
                $query->orderByDesc('costo_total');
                break;
            case 'creacion_asc':
                $query->orderBy('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $cotizaciones = $query->paginate(15);

        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $tiposServicio = TipoServicio::where('empresa_id', $empresaId)->orderBy('nombre')->get();

        return Inertia::render('Cotizaciones/Index', ['cotizaciones' => $cotizaciones, 'aseguradoras' => $aseguradoras, 'tiposServicio' => $tiposServicio]);
    }

    public function buscar(Request $request)
    {
        $user = auth()->user();
        $empresaId = session('empresa_id');

        $query = Cotizacion::where('empresa_id', $empresaId)
            ->with('cliente', 'aseguradora', 'tipoServicio');

        if ($user->isCliente()) {
            $query->where('usuario_creador_id', $user->id);
        }

        if ($request->aseguradora_id) {
            $query->where('aseguradora_id', $request->aseguradora_id);
        }

        if ($request->tipo_servicio_id) {
            $query->where('tipo_servicio_id', $request->tipo_servicio_id);
        }

        if ($request->estatus) {
            $query->where('estatus', $request->estatus);
        }

        if ($desde = $request->desde) {
            $query->whereDate('created_at', '>=', $desde);
        }

        if ($hasta = $request->hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        if ($q = $request->q) {
            $query->where(function ($qry) use ($q) {
                $qry->where('folio', 'like', "%{$q}%")
                    ->orWhere('origen_direccion', 'like', "%{$q}%")
                    ->orWhere('destino_direccion', 'like', "%{$q}%")
                    ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
            });
        }

        $orden = $request->orden ?? 'creacion_desc';
        switch ($orden) {
            case 'cliente_asc':
                $query->orderBy(Cliente::select('nombre')->whereColumn('clientes.id', 'cotizaciones.cliente_id'));
                break;
            case 'cliente_desc':
                $query->orderByDesc(Cliente::select('nombre')->whereColumn('clientes.id', 'cotizaciones.cliente_id'));
                break;
            case 'total_asc':
                $query->orderBy('costo_total');
                break;
            case 'total_desc':
                $query->orderByDesc('costo_total');
                break;
            case 'creacion_asc':
                $query->orderBy('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $cotizaciones = $query->paginate(15);

    }

    public function create()
    {
        $empresaId = session('empresa_id');
        $clientes = Cliente::where('empresa_id', $empresaId)->with('aseguradora')->orderBy('nombre')->get();
        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->with('tiposServicio')->orderBy('nombre')->get();
        $tiposServicio = TipoServicio::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $convenios = Convenio::where('empresa_id', $empresaId)->get(['id', 'nombre', 'aseguradora_id', 'tipo_servicio_id', 'costo_banderazo', 'costo_km', 'km_incluidos', 'cubre_casetas_peaje', 'descuento']);

        return Inertia::render('Cotizaciones/Create', ['clientes' => $clientes, 'aseguradoras' => $aseguradoras, 'tiposServicio' => $tiposServicio, 'convenios' => $convenios]);
    }

    protected function reglasValidacion(): array
    {
        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'aseguradora_id' => ['required', 'exists:aseguradoras,id'],
            'tipo_servicio_id' => ['required', 'exists:tipos_servicio,id'],
            'origen_calle' => ['required', 'string', 'max:255'],
            'origen_num_exterior' => ['nullable', 'string', 'max:20'],
            'origen_num_interior' => ['nullable', 'string', 'max:20'],
            'origen_colonia' => ['required', 'string', 'max:255'],
            'origen_codigo_postal' => ['required', 'string', 'max:10', 'regex:/^[\d]+$/'],
            'origen_localidad' => ['required', 'string', 'max:255'],
            'origen_municipio' => ['required', 'string', 'max:255'],
            'origen_estado' => ['required', 'string', 'max:255'],
            'destino_calle' => ['required', 'string', 'max:255'],
            'destino_num_exterior' => ['nullable', 'string', 'max:20'],
            'destino_num_interior' => ['nullable', 'string', 'max:20'],
            'destino_colonia' => ['required', 'string', 'max:255'],
            'destino_codigo_postal' => ['required', 'string', 'max:10', 'regex:/^[\d]+$/'],
            'destino_localidad' => ['required', 'string', 'max:255'],
            'destino_municipio' => ['required', 'string', 'max:255'],
            'destino_estado' => ['required', 'string', 'max:255'],
            'distancia_km' => ['required', 'numeric', 'min:0.1'],
            'tiempo_estimado_minutos' => ['required', 'integer', 'min:1'],
            'incluye_peajes' => ['boolean'],
            'costo_aprox_casetas' => ['nullable', 'numeric', 'min:0'],
            'num_casetas' => ['nullable', 'integer', 'min:0'],
            'costo_banderazo' => ['required', 'numeric', 'min:0'],
            'costo_km' => ['required', 'numeric', 'min:0'],
            'convenio_aplicado_id' => ['nullable', 'exists:convenios,id'],
            'km_excedente' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'origen_calle.required' => 'La calle de origen es obligatoria.',
            'origen_colonia.required' => 'La colonia de origen es obligatoria.',
            'origen_codigo_postal.required' => 'El código postal de origen es obligatorio.',
            'origen_codigo_postal.regex' => 'El código postal de origen solo puede contener números.',
            'origen_localidad.required' => 'La localidad de origen es obligatoria.',
            'origen_municipio.required' => 'El municipio de origen es obligatorio.',
            'origen_estado.required' => 'El estado de origen es obligatorio.',
            'destino_calle.required' => 'La calle de destino es obligatoria.',
            'destino_colonia.required' => 'La colonia de destino es obligatoria.',
            'destino_codigo_postal.required' => 'El código postal de destino es obligatorio.',
            'destino_codigo_postal.regex' => 'El código postal de destino solo puede contener números.',
            'destino_localidad.required' => 'La localidad de destino es obligatoria.',
            'destino_municipio.required' => 'El municipio de destino es obligatorio.',
            'destino_estado.required' => 'El estado de destino es obligatorio.',
            'distancia_km.required' => 'La distancia es obligatoria.',
            'distancia_km.min' => 'La distancia debe ser mayor a 0.',
            'tiempo_estimado_minutos.required' => 'El tiempo estimado es obligatorio.',
            'tiempo_estimado_minutos.min' => 'El tiempo estimado debe ser al menos 1 minuto.',
            'aseguradora_id.required' => 'Debes seleccionar una aseguradora.',
            'tipo_servicio_id.required' => 'Debes seleccionar un tipo de servicio.',
        ];
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isEmpleado() || auth()->user()->isOperador()) {
            abort(403);
        }

        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $data['empresa_id'] = session('empresa_id');
        $data['incluye_peajes'] = $request->boolean('incluye_peajes');
        $data['costo_aprox_casetas'] = $request->input('costo_aprox_casetas', 0);
        $data['num_casetas'] = $request->input('num_casetas', 0);
        $data['usuario_creador_id'] = auth()->id();
        $data['folio'] = $this->generarFolio();
        $data['km_excedente'] = $request->input('km_excedente', 0);

        $cotizacion = new Cotizacion($data);
        $cotizacion = $this->calcularCostos($cotizacion);
        $cotizacion->estatus = 'pendiente';

        $cotizacion->save();

        $usuarios = User::where('empresa_id', session('empresa_id'))
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_COTIZADOR])
            ->get();

        $notificacionesEnabled = Empresa::find(session('empresa_id'))?->notificaciones_habilitadas ?? true;

        foreach ($usuarios as $u) {
            if ($notificacionesEnabled) {
                Notificacion::create([
                    'empresa_id' => session('empresa_id'),
                    'usuario_id' => $u->id,
                    'mensaje' => "Nueva cotización {$cotizacion->folio} creada. Revisión pendiente.",
                    'tipo' => 'cotizacion_creada',
                    'estado' => 'no_leida',
                ]);
            }
            Cache::forget("notificaciones_no_leidas_{$u->id}");
        }

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización generada correctamente.');
    }

    public function show(Cotizacion $cotizacione)
    {
        $cotizacione->load('cliente', 'aseguradora', 'tipoServicio', 'creador', 'convenio');
        return Inertia::render('Cotizaciones/Show', ['cotizacione' => $cotizacione]);
    }

    public function edit(Cotizacion $cotizacione)
    {
        $empresaId = session('empresa_id');
        $clientes = Cliente::where('empresa_id', $empresaId)->with('aseguradora')->orderBy('nombre')->get();
        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->with('tiposServicio')->orderBy('nombre')->get();
        $tiposServicio = TipoServicio::where('empresa_id', $empresaId)->orderBy('nombre')->get();
        $convenios = Convenio::where('empresa_id', $empresaId)->get(['id', 'nombre', 'aseguradora_id', 'tipo_servicio_id', 'costo_banderazo', 'costo_km', 'km_incluidos', 'cubre_casetas_peaje', 'descuento']);

        return Inertia::render('Cotizaciones/Edit', ['cotizacione' => $cotizacione, 'clientes' => $clientes, 'aseguradoras' => $aseguradoras, 'tiposServicio' => $tiposServicio, 'convenios' => $convenios]);
    }

    public function update(Request $request, Cotizacion $cotizacione)
    {
        if (!auth()->user()->isEmpleado() || auth()->user()->isOperador()) {
            abort(403);
        }

        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $data['incluye_peajes'] = $request->boolean('incluye_peajes');
        $data['costo_aprox_casetas'] = $request->input('costo_aprox_casetas', 0);
        $data['num_casetas'] = $request->input('num_casetas', 0);

        $cotizacione->fill($data);
        $cotizacione = $this->calcularCostos($cotizacione);
        $cotizacione->save();

        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacione)
    {
        if (!auth()->user()->isEmpleado() || auth()->user()->isOperador()) {
            abort(403);
        }

        $cotizacione->delete();
        return redirect()->route('cotizaciones.index')
            ->with('success', 'Cotización eliminada.');
    }

    private function calcularCostos(Cotizacion $cotizacion): Cotizacion
    {
        $kmCobrar = $cotizacion->km_excedente > 0 ? $cotizacion->km_excedente : $cotizacion->distancia_km;
        $costoKilometraje = $kmCobrar * $cotizacion->costo_km;
        $subtotal = $cotizacion->costo_banderazo
            + $costoKilometraje
            + $cotizacion->costo_aprox_casetas;

        if ($cotizacion->convenio_aplicado_id) {
            $convenio = Convenio::find($cotizacion->convenio_aplicado_id);
            if ($convenio && $convenio->descuento > 0) {
                $cotizacion->costo_total = round($subtotal * (1 - $convenio->descuento / 100), 2);
                return $cotizacion;
            }
        }

        $cotizacion->costo_total = $subtotal;
        return $cotizacion;
    }

    private function generarFolio(): string
    {
        $pool = str_split('ABCDEFGHJKLMNPQRSTUVWXYZ23456789');
        do {
            shuffle($pool);
            $code = implode('', array_slice($pool, 0, 6));
            $folio = 'COT-' . $code;
        } while (Cotizacion::where('folio', $folio)->exists() || $this->hasSequence($code));

        return $folio;
    }

    private function hasSequence(string $str): bool
    {
        for ($i = 0; $i < 4; $i++) {
            $a = ord($str[$i]);
            $b = ord($str[$i + 1]);
            $c = ord($str[$i + 2]);
            if ($b - $a === 1 && $c - $b === 1) return true;
            if ($a - $b === 1 && $b - $c === 1) return true;
        }
        return false;
    }
}
