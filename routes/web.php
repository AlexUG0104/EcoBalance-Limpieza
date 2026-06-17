<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AdminController;

// Public & Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Client Routes (Protected)
Route::middleware(['auth', 'client'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/solicitar', [ClienteController::class, 'showSolicitar'])->name('solicitar');
    Route::post('/solicitar', [ClienteController::class, 'solicitar']);
    
    Route::get('/servicios', [ClienteController::class, 'servicios'])->name('servicios');
    Route::get('/servicios/{id}', [ClienteController::class, 'detalle'])->name('detalle');
    Route::post('/servicios/{id}/calificar', [ClienteController::class, 'calificar'])->name('calificar');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Clientes CRUD
    Route::get('/clientes', [AdminController::class, 'clientesIndex'])->name('clientes.index');
    Route::get('/clientes/crear', [AdminController::class, 'clienteCreate'])->name('clientes.create');
    Route::post('/clientes', [AdminController::class, 'clienteStore'])->name('clientes.store');
    Route::get('/clientes/{id}/editar', [AdminController::class, 'clienteEdit'])->name('clientes.edit');
    Route::put('/clientes/{id}', [AdminController::class, 'clienteUpdate'])->name('clientes.update');
    Route::delete('/clientes/{id}', [AdminController::class, 'clienteDestroy'])->name('clientes.destroy');

    // Empleados CRUD
    Route::get('/empleados', [AdminController::class, 'empleadosIndex'])->name('empleados.index');
    Route::get('/empleados/crear', [AdminController::class, 'empleadoCreate'])->name('empleados.create');
    Route::post('/empleados', [AdminController::class, 'empleadoStore'])->name('empleados.store');
    Route::get('/empleados/{id}/editar', [AdminController::class, 'empleadoEdit'])->name('empleados.edit');
    Route::put('/empleados/{id}', [AdminController::class, 'empleadoUpdate'])->name('empleados.update');
    Route::delete('/empleados/{id}', [AdminController::class, 'empleadoDestroy'])->name('empleados.destroy');

    // Servicios CRUD
    Route::get('/servicios', [AdminController::class, 'serviciosIndex'])->name('servicios.index');
    Route::get('/servicios/{id}/editar', [AdminController::class, 'servicioEdit'])->name('servicios.edit');
    Route::put('/servicios/{id}', [AdminController::class, 'servicioUpdate'])->name('servicios.update');
    Route::get('/servicios/{id}', [AdminController::class, 'servicioDetail'])->name('servicios.detail');

    // Monitoreo Panel
    Route::get('/monitoreo', [AdminController::class, 'monitoreoIndex'])->name('monitoreo.index');
});
