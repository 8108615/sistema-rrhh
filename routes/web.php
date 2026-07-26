<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

//Rutas para ajustes
Route::get('/admin/ajustes', [App\Http\Controllers\Admin\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware('auth');
Route::post('/admin/ajustes', [App\Http\Controllers\Admin\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware('auth');

// Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::get('/admin/roles/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth');
Route::post('/admin/roles/create', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');
Route::get('/admin/rol/{id}', [App\Http\Controllers\Admin\RoleController::class, 'show'])->name('admin.roles.show')->middleware('auth');
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth');
Route::get('/admin/rol/{id}/permisos', [App\Http\Controllers\Admin\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth');
Route::put('/admin/rol/{id}/permisos', [App\Http\Controllers\Admin\RoleController::class, 'guardarPermisos'])->name('admin.roles.guardar_permisos')->middleware('auth');
Route::put('/admin/rol/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth');
Route::delete('/admin/rol/{id}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth');

//Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth');
Route::get('/admin/usuarios/create', [App\Http\Controllers\Admin\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth');
Route::post('/admin/usuarios', [App\Http\Controllers\Admin\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');
Route::post('/admin/usuario/{id}/restaurar', [App\Http\Controllers\Admin\UsuarioController::class, 'restaurar'])->name('admin.usuarios.restaurar')->middleware('auth');
Route::get('/admin/usuario/{id}', [App\Http\Controllers\Admin\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth');
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\Admin\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth');
Route::put('/admin/usuario/{id}', [App\Http\Controllers\Admin\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth');
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\Admin\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');

//Rutas para departamentos
Route::get('/admin/departamentos', [App\Http\Controllers\Admin\DepartamentoController::class, 'index'])->name('admin.departamentos.index')->middleware('auth');

Route::post('/admin/departamentos', [App\Http\Controllers\Admin\DepartamentoController::class, 'store'])->name('admin.departamentos.store')->middleware('auth');
Route::put('/admin/departamentos/{departamento}', [App\Http\Controllers\Admin\DepartamentoController::class, 'update'])->name('admin.departamentos.update')->middleware('auth');
Route::delete('/admin/departamentos/{departamento}', [App\Http\Controllers\Admin\DepartamentoController::class, 'destroy'])->name('admin.departamentos.destroy')->middleware('auth');

require __DIR__.'/settings.php';
