<?php

namespace App\Http\Controllers;

use App\Models\Aseguradora;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Operador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authorize('admin');
            return $next($request);
        });
    }

    public function index()
    {
        $empresaId = session('empresa_id');

        $query = User::where('empresa_id', $empresaId)
            ->with('empleado')
            ->orderBy('created_at', 'desc');

        if ($q = request('q')) {
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($rol = request('rol')) {
            $query->where('role', $rol);
        }

        $usuarios = $query->paginate(15);

        return Inertia::render('Usuarios/Index', ['usuarios' => $usuarios]);
    }

    public function buscar(Request $request)
    {
        $empresaId = session('empresa_id');

        $query = User::where('empresa_id', $empresaId)
            ->with('empleado')
            ->orderBy('created_at', 'desc');

        if ($q = $request->q) {
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($rol = $request->rol) {
            $query->where('role', $rol);
        }

        $usuarios = $query->paginate(15);

    }

    public function edit(User $usuario)
    {
        $empresaId = session('empresa_id');

        if ($usuario->empresa_id !== $empresaId) {
            abort(403);
        }

        $usuario->load('cliente', 'empleado.operador');

        $empleados = Empleado::where('empresa_id', $empresaId)
            ->whereDoesntHave('usuario')
            ->orWhere('id', $usuario->empleado_id)
            ->orderBy('nombre')
            ->get();

        $aseguradoras = Aseguradora::where('empresa_id', $empresaId)->orderBy('nombre')->get();

        return Inertia::render('Usuarios/Edit', [
            'usuario' => $usuario,
            'empleados' => $empleados,
            'aseguradoras' => $aseguradoras,
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $empresaId = session('empresa_id');

        if ($usuario->empresa_id !== $empresaId) {
            abort(403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'role' => ['required', 'in:admin,cotizador,operador,cliente'],
            'empleado_id' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];

        if ($request->role === 'cliente') {
            $rules = array_merge($rules, [
                'telefono' => ['required', 'string', 'max:20'],
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
            ]);
        }

        if ($request->role === 'operador') {
            $rules = array_merge($rules, [
                'licencia_tipo' => ['required', 'string', 'max:50', 'regex:/^[\p{L}\p{N}\s\-]+$/u'],
                'licencia_vencimiento_local' => ['required', 'date'],
                'licencia_vencimiento_federal' => ['nullable', 'date'],
                'puntos_acumulados' => ['nullable', 'integer', 'min:0'],
                'disponible' => ['boolean'],
            ]);
        }

        $data = $request->validate($rules);

        $isEmployee = in_array($data['role'], ['admin', 'cotizador', 'operador']);

        if ($isEmployee) {
            if (!$data['empleado_id']) {
                return back()->withErrors(['empleado_id' => 'Debes seleccionar un empleado para este rol.'])->withInput();
            }
            $data['empleado_id'] = (int) $data['empleado_id'];
        } else {
            $data['empleado_id'] = null;
        }

        $oldRole = $usuario->role;
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'empleado_id' => $data['empleado_id'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $usuario->update($updateData);

        if ($data['role'] === 'cliente') {
            Cliente::updateOrCreate(
                ['usuario_id' => $usuario->id],
                $request->only([
                    'telefono', 'aseguradora_id', 'numero_poliza', 'tipo_cobertura_poliza',
                    'calle', 'num_exterior', 'num_interior', 'colonia', 'codigo_postal',
                    'localidad', 'municipio', 'estado',
                ]) + ['empresa_id' => $empresaId, 'nombre' => $data['name']]
            );
        }

        if ($data['role'] === 'operador') {
            Operador::updateOrCreate(
                ['empleado_id' => $data['empleado_id'], 'empresa_id' => $empresaId],
                [
                    'licencia_tipo' => $data['licencia_tipo'],
                    'licencia_año_vencimiento' => $data['licencia_vencimiento_local'],
                    'licencia_vencimiento_federal' => $data['licencia_vencimiento_federal'] ?? null,
                    'puntos_acumulados' => $data['puntos_acumulados'] ?? 0,
                    'disponible' => $request->boolean('disponible'),
                ]
            );
        }

        if ($data['role'] !== 'operador' && $oldRole === 'operador') {
            Operador::where('empleado_id', $usuario->empleado_id)->delete();
        }

        if ($data['role'] === 'cotizador') {
            Cotizador::firstOrCreate(
                ['empleado_id' => $data['empleado_id'], 'empresa_id' => $empresaId],
                ['activo' => true]
            );
        }

        if ($data['role'] !== 'cotizador' && $oldRole === 'cotizador') {
            Cotizador::where('empleado_id', $usuario->empleado_id)->delete();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $empresaId = session('empresa_id');

        if ($usuario->empresa_id !== $empresaId) {
            abort(403);
        }

        if ($usuario->id === auth()->id()) {
            abort(403, 'No puedes eliminar tu propia cuenta.');
        }

        if (!$usuario->wasCreatedBy(auth()->id())) {
            abort(403, 'Solo puedes eliminar usuarios que tú creaste.');
        }

        $empleado = $usuario->empleado;

        $usuario->delete();

        if ($empleado && !$empleado->usuario) {
            if ($empleado->operador) {
                $empleado->operador->delete();
            }
            if ($empleado->cotizador) {
                $empleado->cotizador->delete();
            }
            $empleado->delete();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
