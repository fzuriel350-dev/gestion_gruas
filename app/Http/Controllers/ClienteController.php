<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authorize('empleado');
            return $next($request);
        });
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->isAdmin()) abort(403);
    }

    public function index()
    {
        $empresaId = session('empresa_id');

        $query = Cliente::where('empresa_id', $empresaId)
            ->with('aseguradora')
            ->withCount('servicios');

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('nombre', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $clientes = $query->orderBy('nombre')->paginate(15);

        return Inertia::render('Clientes/Index', ['clientes' => $clientes]);
    }

    public function buscar(Request $request)
    {
        $empresaId = session('empresa_id');

        $query = Cliente::where('empresa_id', $empresaId)
            ->with('aseguradora')
            ->withCount('servicios');

        if ($q = $request->q) {
            $query->where(function ($qry) use ($q) {
                $qry->where('nombre', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $clientes = $query->orderBy('nombre')->paginate(15);

    }

    public function create()
    {
        $this->authorizeAdmin();
        $aseguradoras = \App\Models\Aseguradora::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return Inertia::render('Clientes/Create', ['aseguradoras' => $aseguradoras]);
    }

    protected function reglasValidacion(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[\p{Lu}][\p{L}\s]+$/u'],
            'telefono' => ['required', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'email' => ['required', 'email', 'max:255'],
            'aseguradora_id' => ['required', 'exists:aseguradoras,id'],
            'numero_poliza' => ['required', 'string', 'max:50'],
            'tipo_cobertura_poliza' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
            'calle' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'num_exterior' => ['nullable', 'string', 'max:20', 'regex:/^[\p{L}\p{N}\s]*$/u'],
            'num_interior' => ['nullable', 'string', 'max:20', 'regex:/^[\p{L}\p{N}\s]*$/u'],
            'colonia' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'codigo_postal' => ['required', 'string', 'max:10', 'regex:/^[\d]+$/'],
            'localidad' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'municipio' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'estado' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y signo +.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe contener un @ para ser válido.',
            'aseguradora_id.required' => 'La aseguradora es obligatoria.',
            'aseguradora_id.exists' => 'La aseguradora seleccionada no es válida.',
            'calle.required' => 'La calle es obligatoria.',
            'calle.regex' => 'La calle solo puede contener letras y espacios.',
            'colonia.required' => 'La colonia es obligatoria.',
            'colonia.regex' => 'La colonia solo puede contener letras y espacios.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'numero_poliza.required' => 'El número de póliza es obligatorio.',
            'tipo_cobertura_poliza.required' => 'El tipo de cobertura es obligatorio.',
            'tipo_cobertura_poliza.regex' => 'El tipo de cobertura solo puede contener letras y espacios.',
            'codigo_postal.regex' => 'El código postal solo puede contener números.',
            'localidad.required' => 'La localidad es obligatoria.',
            'localidad.regex' => 'La localidad solo puede contener letras y espacios.',
            'municipio.required' => 'El municipio es obligatorio.',
            'municipio.regex' => 'El municipio solo puede contener letras y espacios.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.regex' => 'El estado solo puede contener letras y espacios.',
            'num_exterior.regex' => 'El número exterior solo puede contener letras y números.',
            'num_interior.regex' => 'El número interior solo puede contener letras y números.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $data['empresa_id'] = session('empresa_id');
        Cliente::create($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('cotizaciones', 'aseguradora');
        return Inertia::render('Clientes/Show', ['cliente' => $cliente]);
    }

    public function edit(Cliente $cliente)
    {
        $this->authorizeAdmin();
        $aseguradoras = \App\Models\Aseguradora::where('empresa_id', session('empresa_id'))
            ->orderBy('nombre')
            ->get();
        return Inertia::render('Clientes/Edit', ['cliente' => $cliente, 'aseguradoras' => $aseguradoras]);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->authorizeAdmin();
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());

        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->authorizeAdmin();
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
