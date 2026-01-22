<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\usuario\PortalController as UPortal;
use App\Http\Controllers\GimnasioContextController;
use App\Http\Controllers\Admin\ContextoGimnasioController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\UsuarioClasesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Definición del mapa de rutas del sistema de gestión de gimnasios.
 * * Este archivo organiza los endpoints en tres grandes bloques:
 * 1. **Acceso Público y Global**: Login y redirección inicial por rol.
 * 2. **Administración de Recursos (Staff)**: Rutas para Admin, Monitor y Limpieza 
 * protegidas por el middleware 'rol' y el guard por defecto.
 * 3. **Portal del Socio (Clientes)**: Rutas bajo el guard 'usuario' y prefijo '/usuario'.
*/


// Página de bienvenida
Route::view('/', 'welcome');

// Rutas de gestión de sesión para empleados/admin
Route::get('/login',  [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

// Punto de control para redirigir a los usuarios según su rol asignado
Route::middleware('auth')->get('/panel', [DashboardController::class,'redirectByRole'])->name('panel');


/**
 * Rutas de Gestión de Servicios y Empleados.
 * * Acceso exclusivo para usuarios con rol 'Administrador'.
 */
// Paneles por rol
Route::middleware(['auth','rol:Administrador'])->get('/panel/admin', [DashboardController::class,'admin'])->name('panel.admin');
Route::middleware(['auth','rol:Monitor,Limpieza'])->get('/panel/empleado', [DashboardController::class,'empleado'])->name('panel.empleado');
Route::middleware(['auth','rol:Administrador'])->group(function () {
    Route::resource('servicios', ServicioController::class)->except(['show']);
});

// Admin y Monitor: todo menos borrar
Route::middleware(['auth','rol:Administrador,Monitor'])->group(function () {
    Route::resource('suscripciones', SuscripcionController::class)->except(['show','destroy']);
});

// Admin y Monitor: todo menos borrar
Route::middleware(['auth','rol:Administrador,Monitor'])->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['show','destroy']);
});

Route::middleware(['auth','rol:Administrador'])->group(function () {
    Route::resource('empleados', EmpleadoController::class)->except(['show']);
});

// Monitor y Administrador: SOLO VER (index)
Route::middleware(['auth','rol:Administrador,Monitor'])->group(function () {
    Route::get('asignaciones', [AsignacionController::class, 'index'])->name('asignaciones.index');
});

// SOLO Administrador: crear/editar/borrar
Route::middleware(['auth','rol:Administrador'])->group(function () {
    Route::get('asignaciones/create', [AsignacionController::class, 'create'])->name('asignaciones.create');
    Route::post('asignaciones', [AsignacionController::class, 'store'])->name('asignaciones.store');
    Route::get('asignaciones/{asignacione}/edit', [AsignacionController::class, 'edit'])->name('asignaciones.edit');
    Route::put('asignaciones/{asignacione}', [AsignacionController::class, 'update'])->name('asignaciones.update');
    Route::delete('asignaciones/{asignacione}', [AsignacionController::class, 'destroy'])->name('asignaciones.destroy');
});

/*
|--------------------------------------------------------------------------
| Portal Privado del Socio (Guard: usuario)
|--------------------------------------------------------------------------
|
| Rutas destinadas al cliente final para la gestión de su perfil personal,
| cambio de credenciales y acceso a material deportivo (rutinas).
|
*/
Route::prefix('usuario')->middleware('auth:usuario')->group(function () {
    Route::get('/panel',               [UPortal::class, 'panel'])->name('u.panel');
    Route::get('/perfil',              [UPortal::class, 'perfil'])->name('u.perfil');
    Route::post('/perfil/password',    [UPortal::class, 'updatePassword'])->name('u.perfil.password');
    Route::get('/rutinas',             [UPortal::class, 'rutinas'])->name('u.rutinas');
    Route::get('/clases', [UsuarioClasesController::class, 'index'])->name('u.clases');
});


// Gimnasio Contexto (selección de gimnasio)
Route::middleware(['auth','rol:Administrador'])->group(function () {
    Route::post('/admin/contexto-gimnasio', [ContextoGimnasioController::class,'set'])
        ->name('admin.ctx.gimnasio.set');

    Route::post('/admin/contexto-gimnasio/clear', [ContextoGimnasioController::class,'clear'])
        ->name('admin.ctx.gimnasio.clear');
});

// =====================
// PLANES (solo Admin)
// =====================
Route::middleware(['auth','rol:Administrador'])->group(function () {
    Route::resource('planes', PlanController::class)
        ->parameters(['planes' => 'plan'])
        ->except(['show']);
});

//ruta pagos
Route::middleware(['auth','rol:Administrador,Monitor'])->group(function () {
    Route::get('suscripciones/{suscripcion}/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('suscripciones/{suscripcion}/pagos/create', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('suscripciones/{suscripcion}/pagos', [PagoController::class, 'store'])->name('pagos.store');

    // NUEVAS
    Route::get('suscripciones/{suscripcion}/pagos/{pago}/edit', [PagoController::class, 'edit'])->name('pagos.edit');
    Route::put('suscripciones/{suscripcion}/pagos/{pago}', [PagoController::class, 'update'])->name('pagos.update');
});







