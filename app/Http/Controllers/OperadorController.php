<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OperadorController extends Controller
{
    public function index()
    {
        $this->authorize('empleado');
        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado', 'unidades')
            ->orderBy('id')
            ->paginate(15);

        return Inertia::render('Operadores/Index', ['operadores' => $operadores]);
    }

    public function buscar(Request $request)
    {
        $this->authorize('empleado');
        $operadores = Operador::where('empresa_id', session('empresa_id'))
            ->with('empleado', 'unidades')
            ->orderBy('id')
            ->paginate(15);

    }

    public function create()
    {
        $this->authorize('empleado');
        $empleados = Empleado::where('empresa_id', session('empresa_id'))
            ->whereDoesntHave('operador')
            ->orderBy('nombre')
            ->get();
        return Inertia::render('Operadores/Create', ['empleados' => $empleados]);
    }

    protected function reglasValidacion(): array
    {
        return [
            'empleado_id' => ['required', 'exists:empleados,id'],
            'licencia_tipo' => ['required', 'string', 'max:50', 'regex:/^[\p{L}\p{N}\s\-]+$/u'],
            'licencia_año_vencimiento' => ['required', 'date'],
            'licencia_vencimiento_federal' => ['nullable', 'date'],
            'disponible' => ['boolean'],
            'puntos_acumulados' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'empleado_id.required' => 'Selecciona un empleado.',
            'licencia_tipo.required' => 'El tipo de licencia es obligatorio.',
            'licencia_año_vencimiento.required' => 'La fecha de vencimiento de la licencia es obligatoria.',
        ];
    }

    public function store(Request $request)
    {
        $this->authorize('empleado');
        $data = $request->validate($this->reglasValidacion(), $this->mensajesValidacion());
        $data['empresa_id'] = session('empresa_id');
        $data['disponible'] = $request->boolean('disponible');
        Operador::create($data);
        return redirect()->route('operadores.index')->with('success', 'Operador registrado correctamente.');
    }

    public function show(Operador $operador)
    {
        $this->authorize('empleado');
        $operador->load('empleado', 'unidades');
        return Inertia::render('Operadores/Show', ['operador' => $operador]);
    }

    public function edit(Operador $operador)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isCotizador()) {
            abort(403);
        }
        $empleados = Empleado::where('empresa_id', session('empresa_id'))
            ->where(function ($q) use ($operador) {
                $q->whereDoesntHave('operador')->orWhere('id', $operador->empleado_id);
            })
            ->orderBy('nombre')
            ->get();
        return Inertia::render('Operadores/Edit', ['operador' => $operador, 'empleados' => $empleados]);
    }

    public function update(Request $request, Operador $operador)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isCotizador()) {
            abort(403);
        }
        $data = $request->validate([
            'empleado_id' => ['required', 'exists:empleados,id'],
            'licencia_tipo' => ['sometimes', 'string', 'max:50', 'regex:/^[\p{L}\p{N}\s\-]+$/u'],
            'licencia_año_vencimiento' => ['sometimes', 'date'],
            'licencia_vencimiento_federal' => ['nullable', 'date'],
            'disponible' => ['boolean'],
            'puntos_acumulados' => ['nullable', 'integer', 'min:0'],
        ], $this->mensajesValidacion());
        $data['disponible'] = $request->boolean('disponible');
        $operador->update($data);
        return redirect()->route('operadores.index')->with('success', 'Operador actualizado correctamente.');
    }

    public function destroy(Operador $operador)
    {
        $this->authorize('admin');
        $operador->delete();
        return redirect()->route('operadores.index')->with('success', 'Operador eliminado.');
    }
}
