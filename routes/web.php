<?php

use App\Http\Controllers\AseguradoraController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClientePanelController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\AutorizacionCancelacionController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['patch', 'post'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('cotizaciones/buscar', [CotizacionController::class, 'buscar'])->name('cotizaciones.buscar')->middleware('throttle:30,1');
    Route::resource('cotizaciones', CotizacionController::class);
    Route::get('clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar')->middleware('throttle:30,1');
    Route::resource('clientes', ClienteController::class);

    Route::get('servicios/buscar', [ServicioController::class, 'buscar'])->name('servicios.buscar')->middleware('throttle:30,1');
    Route::get('notificaciones/buscar', [NotificacionController::class, 'buscar'])->name('notificaciones.buscar')->middleware('throttle:30,1');
    Route::get('usuarios/buscar', [UserController::class, 'buscar'])->name('usuarios.buscar')->middleware('throttle:30,1');

    Route::get('empleados/buscar', [EmpleadoController::class, 'buscar'])->name('empleados.buscar')->middleware('throttle:30,1');
    Route::get('operadores/buscar', [OperadorController::class, 'buscar'])->name('operadores.buscar')->middleware('throttle:30,1');
    Route::get('unidades/buscar', [UnidadController::class, 'buscar'])->name('unidades.buscar')->middleware('throttle:30,1');
    Route::get('oficinas/buscar', [OficinaController::class, 'buscar'])->name('oficinas.buscar')->middleware('throttle:30,1');
    Route::get('convenios/buscar', [ConvenioController::class, 'buscar'])->name('convenios.buscar')->middleware('throttle:30,1');
    Route::get('aseguradoras/buscar', [AseguradoraController::class, 'buscar'])->name('aseguradoras.buscar')->middleware('throttle:30,1');
    Route::get('facturas/buscar', [FacturaController::class, 'buscar'])->name('facturas.buscar')->middleware('throttle:30,1');
    Route::get('autorizaciones-cancelacion/buscar', [AutorizacionCancelacionController::class, 'buscar'])->name('autorizaciones-cancelacion.buscar')->middleware('throttle:30,1');
    Route::get('tipos-servicio/buscar', [TipoServicioController::class, 'buscar'])->name('tipos-servicio.buscar')->middleware('throttle:30,1');

    Route::resource('aseguradoras', AseguradoraController::class);
    Route::resource('tipos-servicio', TipoServicioController::class)->parameters(['tipos-servicio' => 'tiposServicio']);
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('operadores', OperadorController::class)->parameters(['operadores' => 'operador']);
    Route::resource('unidades', UnidadController::class);
    Route::resource('oficinas', OficinaController::class);
    Route::resource('autorizaciones-cancelacion', AutorizacionCancelacionController::class);
    Route::resource('facturas', FacturaController::class)->only('index', 'show', 'destroy');
    Route::get('facturas/{factura}/descargar-pdf', [FacturaController::class, 'descargarPdf'])->name('facturas.descargar-pdf');
    Route::resource('convenios', ConvenioController::class);
    Route::get('convenios/aseguradora/{aseguradora}/tipos', [ConvenioController::class, 'tiposPorAseguradora'])->name('convenios.tipos-por-aseguradora');
    Route::resource('servicios', ServicioController::class);
    Route::patch('servicios/{servicio}/avanzar', [ServicioController::class, 'avanzarEstado'])->name('servicios.avanzar');
    Route::post('servicios/{servicio}/liberar', [ServicioController::class, 'liberar'])->name('servicios.liberar');
    Route::post('servicios/{servicio}/cancelar', [ServicioController::class, 'cancelarPorCotizador'])->name('servicios.cancelar');
    Route::post('servicios/{servicio}/asignar-operador', [ServicioController::class, 'asignarOperador'])->name('servicios.asignar-operador');
    Route::post('servicios/{servicio}/generar-factura', [ServicioController::class, 'generarFactura'])->name('servicios.generar-factura');
    Route::resource('usuarios', UserController::class);

    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::patch('notificaciones/{notificacione}/leer', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('notificaciones/leer-todas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leer-todas');

    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');

        Route::prefix('panel')->name('clientes.')->group(function () {
        Route::get('/servicios', [ClientePanelController::class, 'servicios'])->name('servicios');
        Route::get('/servicios/{servicio}', [ClientePanelController::class, 'servicioShow'])->name('servicio-show');
        Route::post('/servicios/{servicio}/cancelar', [ClientePanelController::class, 'cancelarServicio'])->name('servicios.cancelar');
        Route::get('/cotizaciones', [ClientePanelController::class, 'cotizaciones'])->name('cotizaciones');
        Route::post('/cotizaciones/{cotizacione}/aprobar', [ClientePanelController::class, 'aprobarCotizacion'])->name('cotizaciones.aprobar');
        Route::post('/cotizaciones/{cotizacione}/rechazar', [ClientePanelController::class, 'rechazarCotizacion'])->name('cotizaciones.rechazar');
        Route::get('/notificaciones', [ClientePanelController::class, 'notificaciones'])->name('notificaciones');
        Route::post('/notificaciones/{notificacione}/leer', [ClientePanelController::class, 'notificacionLeer'])->name('notificaciones.leer');
        Route::post('/notificaciones/leer-todas', [ClientePanelController::class, 'notificacionesLeerTodas'])->name('notificaciones.leer-todas');
        Route::get('/perfil', [ClientePanelController::class, 'perfil'])->name('perfil');
        Route::post('/perfil', [ClientePanelController::class, 'updatePerfil'])->name('perfil.update');
    });

    Route::prefix('cotizador')->name('cotizador.')->group(function () {
        Route::get('/notificaciones', [NotificacionController::class, 'cotizadorIndex'])->name('notificaciones');
        Route::post('/notificaciones/{notificacione}/leer', [NotificacionController::class, 'cotizadorMarcarLeida'])->name('notificaciones.leer');
        Route::post('/notificaciones/leer-todas', [NotificacionController::class, 'cotizadorMarcarTodasLeidas'])->name('notificaciones.leer-todas');
    });

    Route::prefix('operador')->name('operador.')->group(function () {
        Route::get('/notificaciones', [NotificacionController::class, 'operadorIndex'])->name('notificaciones');
        Route::post('/notificaciones/{notificacione}/leer', [NotificacionController::class, 'operadorMarcarLeida'])->name('notificaciones.leer');
        Route::post('/notificaciones/leer-todas', [NotificacionController::class, 'operadorMarcarTodasLeidas'])->name('notificaciones.leer-todas');
    });
});

require __DIR__.'/auth.php';
