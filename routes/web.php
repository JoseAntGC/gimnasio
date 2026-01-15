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

Route::view('/', 'welcome');

// Auth (sin middlewares especiales)
Route::get('/login',  [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

// Panel genérico (luego redirige según rol)
Route::middleware('auth')->get('/panel', [DashboardController::class,'redirectByRole'])->name('panel');

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

// Solo Admin: borrar
Route::middleware(['auth','rol:Administrador'])->group(function () {
    Route::delete('suscripciones/{suscripcione}', [SuscripcionController::class,'destroy'])
        ->name('suscripciones.destroy');
});

// Admin y Monitor: todo menos borrar
Route::middleware(['auth','rol:Administrador,Monitor'])->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['show','destroy']);
});

// Solo Admin: borrar
Route::middleware(['auth','rol:Administrador'])->group(function () {
    Route::delete('usuarios/{usuario}', [UsuarioController::class,'destroy'])
        ->name('usuarios.destroy'); 
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

Route::prefix('usuario')->middleware('auth:usuario')->group(function () {
    Route::get('/panel',               [UPortal::class, 'panel'])->name('u.panel');
    Route::get('/perfil',              [UPortal::class, 'perfil'])->name('u.perfil');
    Route::post('/perfil/password',    [UPortal::class, 'updatePassword'])->name('u.perfil.password');
    Route::get('/rutinas',             [UPortal::class, 'rutinas'])->name('u.rutinas');
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


