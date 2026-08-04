<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');
    // Si quieres que al entrar a /dashboard o /admin/dashboard redirija o cargue lo mismo:
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
});

//Rutas para ajustes
Route::get('/admin/ajustes', [App\Http\Controllers\Admin\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware(['auth', 'can:admin.ajustes.index']);
Route::post('/admin/ajustes', [App\Http\Controllers\Admin\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware(['auth', 'can:admin.ajustes.store']);

// Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.roles.index')->middleware(['auth', 'can:admin.roles.index']);
Route::get('/admin/roles/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('admin.roles.create')->middleware(['auth', 'can:admin.roles.create']);
Route::post('/admin/roles/create', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('admin.roles.store')->middleware(['auth', 'can:admin.roles.create']);
Route::get('/admin/rol/{id}', [App\Http\Controllers\Admin\RoleController::class, 'show'])->name('admin.roles.show')->middleware(['auth', 'can:admin.roles.index']);
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware(['auth', 'can:admin.roles.edit']);
Route::get('/admin/rol/{id}/permisos', [App\Http\Controllers\Admin\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware(['auth', 'can:admin.roles.permisos']);
Route::put('/admin/rol/{id}/permisos', [App\Http\Controllers\Admin\RoleController::class, 'guardarPermisos'])->name('admin.roles.guardar_permisos')->middleware(['auth', 'can:admin.roles.permisos']);
Route::put('/admin/rol/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('admin.roles.update')->middleware(['auth', 'can:admin.roles.edit']);
Route::delete('/admin/rol/{id}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware(['auth', 'can:admin.roles.destroy']);

//Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware(['auth', 'can:admin.usuarios.index']);
Route::get('/admin/usuarios/create', [App\Http\Controllers\Admin\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware(['auth', 'can:admin.usuarios.create']);
Route::post('/admin/usuarios', [App\Http\Controllers\Admin\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware(['auth', 'can:admin.usuarios.create']);
Route::post('/admin/usuario/{id}/restaurar', [App\Http\Controllers\Admin\UsuarioController::class, 'restaurar'])->name('admin.usuarios.restaurar')->middleware(['auth', 'can:admin.usuarios.edit']);
Route::get('/admin/usuario/{id}', [App\Http\Controllers\Admin\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware(['auth', 'can:admin.usuarios.index']);
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\Admin\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware(['auth', 'can:admin.usuarios.edit']);
Route::put('/admin/usuario/{id}', [App\Http\Controllers\Admin\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware(['auth', 'can:admin.usuarios.edit']);
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\Admin\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware(['auth', 'can:admin.usuarios.destroy']);

//Rutas para departamentos
Route::get('/admin/departamentos', [App\Http\Controllers\Admin\DepartamentoController::class, 'index'])->name('admin.departamentos.index')->middleware(['auth', 'can:admin.departamentos.index']);
Route::post('/admin/departamentos', [App\Http\Controllers\Admin\DepartamentoController::class, 'store'])->name('admin.departamentos.store')->middleware(['auth', 'can:admin.departamentos.store']);
Route::put('/admin/departamentos/{departamento}', [App\Http\Controllers\Admin\DepartamentoController::class, 'update'])->name('admin.departamentos.update')->middleware(['auth', 'can:admin.departamentos.update']);
Route::delete('/admin/departamentos/{departamento}', [App\Http\Controllers\Admin\DepartamentoController::class, 'destroy'])->name('admin.departamentos.destroy')->middleware(['auth', 'can:admin.departamentos.destroy']);

//Rutas para areas
Route::get('/admin/areas', [App\Http\Controllers\Admin\AreaController::class, 'index'])->name('admin.areas.index')->middleware(['auth', 'can:admin.areas.index']);
Route::post('/admin/areas', [App\Http\Controllers\Admin\AreaController::class, 'store'])->name('admin.areas.store')->middleware(['auth', 'can:admin.areas.store']);
Route::put('/admin/areas/{area}', [App\Http\Controllers\Admin\AreaController::class, 'update'])->name('admin.areas.update')->middleware(['auth', 'can:admin.areas.update']);
Route::delete('/admin/areas/{area}', [App\Http\Controllers\Admin\AreaController::class, 'destroy'])->name('admin.areas.destroy')->middleware(['auth', 'can:admin.areas.destroy']);

// Rutas para cargos
Route::get('/admin/cargos', [App\Http\Controllers\Admin\CargoController::class, 'index'])->name('admin.cargos.index')->middleware(['auth', 'can:admin.cargos.index']);
Route::post('/admin/cargos', [App\Http\Controllers\Admin\CargoController::class, 'store'])->name('admin.cargos.store')->middleware(['auth', 'can:admin.cargos.store']);
Route::put('/admin/cargos/{cargo}', [App\Http\Controllers\Admin\CargoController::class, 'update'])->name('admin.cargos.update')->middleware(['auth', 'can:admin.cargos.update']);
Route::delete('/admin/cargos/{cargo}', [App\Http\Controllers\Admin\CargoController::class, 'destroy'])->name('admin.cargos.destroy')->middleware(['auth', 'can:admin.cargos.destroy']);

// Rutas para empleados
Route::get('/admin/empleados', [App\Http\Controllers\Admin\EmpleadoController::class, 'index'])->name('admin.empleados.index')->middleware(['auth', 'can:admin.empleados.index']);
Route::get('/admin/empleados/create', [App\Http\Controllers\Admin\EmpleadoController::class, 'create'])->name('admin.empleados.create')->middleware(['auth', 'can:admin.empleados.create']);
Route::post('/admin/empleados', [App\Http\Controllers\Admin\EmpleadoController::class, 'store'])->name('admin.empleados.store')->middleware(['auth', 'can:admin.empleados.create']);
Route::get('/admin/empleados/{id}', [App\Http\Controllers\Admin\EmpleadoController::class, 'show'])->name('admin.empleados.show')->middleware(['auth', 'can:admin.empleados.index']);
Route::get('/admin/empleados/{empleado}/edit', [App\Http\Controllers\Admin\EmpleadoController::class, 'edit'])->name('admin.empleados.edit')->middleware(['auth', 'can:admin.empleados.edit']);
Route::put('/admin/empleados/{empleado}', [App\Http\Controllers\Admin\EmpleadoController::class, 'update'])->name('admin.empleados.update')->middleware(['auth', 'can:admin.empleados.edit']);
Route::delete('/admin/empleados/{empleado}', [App\Http\Controllers\Admin\EmpleadoController::class, 'destroy'])->name('admin.empleados.destroy')->middleware(['auth', 'can:admin.empleados.destroy']);

// Rutas para Planillas
Route::get('/admin/planillas', [App\Http\Controllers\Admin\PlanillaController::class, 'index'])->name('admin.planillas.index')->middleware(['auth', 'can:admin.planillas.index']);
Route::get('/admin/planillas/create', [App\Http\Controllers\Admin\PlanillaController::class, 'create'])->name('admin.planillas.create')->middleware(['auth', 'can:admin.planillas.create']);
Route::post('/admin/planillas', [App\Http\Controllers\Admin\PlanillaController::class, 'store'])->name('admin.planillas.store')->middleware(['auth', 'can:admin.planillas.create']);
Route::get('/admin/planillas/{id}', [App\Http\Controllers\Admin\PlanillaController::class, 'show'])->name('admin.planillas.show')->middleware(['auth', 'can:admin.planillas.index']);
Route::get('/admin/planillas/{planilla}/edit', [App\Http\Controllers\Admin\PlanillaController::class, 'edit'])->name('admin.planillas.edit')->middleware(['auth', 'can:admin.planillas.edit']);
Route::put('/admin/planillas/{planilla}', [App\Http\Controllers\Admin\PlanillaController::class, 'update'])->name('admin.planillas.update')->middleware(['auth', 'can:admin.planillas.edit']);
Route::delete('/admin/planillas/{planilla}', [App\Http\Controllers\Admin\PlanillaController::class, 'destroy'])->name('admin.planillas.destroy')->middleware(['auth', 'can:admin.planillas.destroy']);
Route::get('/admin/planillas/{id}/pdf', [App\Http\Controllers\Admin\PlanillaController::class, 'pdf'])->name('admin.planillas.pdf')->middleware(['auth', 'can:admin.planillas.index']);
Route::patch('/admin/planillas/{planilla}/pagar', [App\Http\Controllers\Admin\PlanillaController::class, 'marcarComoPagado'])->name('admin.planillas.pagar')->middleware(['auth', 'can:admin.planillas.edit']);

// Rutas para pagos de empleados
Route::get('/admin/pagos', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'index'])->name('admin.pagos.index')->middleware(['auth', 'can:admin.pagos.index']);
Route::get('/admin/pagos/create', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'create'])->name('admin.pagos.create')->middleware(['auth', 'can:admin.pagos.create']);
Route::post('/admin/pagos', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'store'])->name('admin.pagos.store')->middleware(['auth', 'can:admin.pagos.create']);
Route::get('/admin/pagos/{id}/print', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'print'])->name('admin.pagos.print')->middleware(['auth', 'can:admin.pagos.index']);
Route::get('/admin/pagos/{id}', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'show'])->name('admin.pagos.show')->middleware(['auth', 'can:admin.pagos.index']);
Route::get('/admin/pagos/{id}/edit', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'edit'])->name('admin.pagos.edit')->middleware(['auth', 'can:admin.pagos.edit']);
Route::put('/admin/pagos/{id}', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'update'])->name('admin.pagos.update')->middleware(['auth', 'can:admin.pagos.edit']);
Route::delete('/admin/pagos/{id}', [App\Http\Controllers\Admin\PagoEmpleadoController::class, 'destroy'])->name('admin.pagos.destroy')->middleware(['auth', 'can:admin.pagos.destroy']);

// Rutas para Permisos y Vacaciones
Route::get('/admin/permisos', [App\Http\Controllers\Admin\PermisoController::class, 'index'])->name('admin.permisos.index')->middleware(['auth', 'can:admin.permisos.index']);
Route::get('/admin/permisos/create', [App\Http\Controllers\Admin\PermisoController::class, 'create'])->name('admin.permisos.create')->middleware(['auth', 'can:admin.permisos.create']);
Route::get('/admin/permisos/empleado/{id}/vacaciones', [App\Http\Controllers\Admin\PermisoController::class, 'getVacacionesEmpleado'])->name('admin.permisos.vacaciones')->middleware(['auth', 'can:admin.permisos.create']);
Route::post('/admin/permisos', [App\Http\Controllers\Admin\PermisoController::class, 'store'])->name('admin.permisos.store')->middleware(['auth', 'can:admin.permisos.create']);
Route::get('/admin/permisos/{id}/edit', [App\Http\Controllers\Admin\PermisoController::class, 'edit'])->name('admin.permisos.edit')->middleware(['auth', 'can:admin.permisos.edit']);
Route::put('/admin/permisos/{id}', [App\Http\Controllers\Admin\PermisoController::class, 'update'])->name('admin.permisos.update')->middleware(['auth', 'can:admin.permisos.edit']);
Route::delete('/admin/permisos/{id}', [App\Http\Controllers\Admin\PermisoController::class, 'destroy'])->name('admin.permisos.destroy')->middleware(['auth', 'can:admin.permisos.destroy']);

// Ruta adicional opcional por si quieres cambiar el estado rápidamente (Aprobar / Rechazar)
Route::patch('/admin/permisos/{id}/estado', [App\Http\Controllers\Admin\PermisoController::class, 'cambiarEstado'])->name('admin.permisos.estado')->middleware(['auth', 'can:admin.permisos.edit']);

// Rutas para Finiquitos
Route::get('/admin/finiquitos', [App\Http\Controllers\Admin\FiniquitoController::class, 'index'])->name('admin.finiquitos.index')->middleware(['auth', 'can:admin.finiquitos.index']);
Route::get('/admin/finiquitos/create', [App\Http\Controllers\Admin\FiniquitoController::class, 'create'])->name('admin.finiquitos.create')->middleware(['auth', 'can:admin.finiquitos.create']);
Route::post('/admin/finiquitos', [App\Http\Controllers\Admin\FiniquitoController::class, 'store'])->name('admin.finiquitos.store')->middleware(['auth', 'can:admin.finiquitos.create']);
Route::get('/admin/finiquitos/{id}', [App\Http\Controllers\Admin\FiniquitoController::class, 'show'])->name('admin.finiquitos.show')->middleware(['auth', 'can:admin.finiquitos.index']);
Route::delete('/admin/finiquitos/{id}', [App\Http\Controllers\Admin\FiniquitoController::class, 'destroy'])->name('admin.finiquitos.destroy')->middleware(['auth', 'can:admin.finiquitos.destroy']);
Route::get('/admin/finiquitos/{id}/print', [App\Http\Controllers\Admin\FiniquitoController::class, 'print'])->name('admin.finiquitos.print')->middleware(['auth', 'can:admin.finiquitos.index']);

// Rutas para Aguinaldos
Route::get('/admin/aguinaldos', [App\Http\Controllers\Admin\AguinaldoController::class, 'index'])->name('admin.aguinaldos.index')->middleware(['auth', 'can:admin.aguinaldos.index']);
Route::get('/admin/aguinaldos/create', [App\Http\Controllers\Admin\AguinaldoController::class, 'create'])->name('admin.aguinaldos.create')->middleware(['auth', 'can:admin.aguinaldos.create']);
Route::post('/admin/aguinaldos', [App\Http\Controllers\Admin\AguinaldoController::class, 'store'])->name('admin.aguinaldos.store')->middleware(['auth', 'can:admin.aguinaldos.create']);
Route::post('/admin/aguinaldos/calcular', [App\Http\Controllers\Admin\AguinaldoController::class, 'calcularMasivo'])->name('admin.aguinaldos.calcular')->middleware(['auth', 'can:admin.aguinaldos.create']);
Route::get('/admin/aguinaldos/{id}/edit', [App\Http\Controllers\Admin\AguinaldoController::class, 'edit'])->name('admin.aguinaldos.edit')->middleware(['auth', 'can:admin.aguinaldos.edit']);
Route::put('/admin/aguinaldos/{id}', [App\Http\Controllers\Admin\AguinaldoController::class, 'update'])->name('admin.aguinaldos.update')->middleware(['auth', 'can:admin.aguinaldos.edit']);
Route::get('/admin/aguinaldos/{id}', [App\Http\Controllers\Admin\AguinaldoController::class, 'show'])->name('admin.aguinaldos.show')->middleware(['auth', 'can:admin.aguinaldos.index']);
Route::delete('/admin/aguinaldos/{id}', [App\Http\Controllers\Admin\AguinaldoController::class, 'destroy'])->name('admin.aguinaldos.destroy')->middleware(['auth', 'can:admin.aguinaldos.destroy']);
Route::get('/admin/aguinaldos/{id}/print', [App\Http\Controllers\Admin\AguinaldoController::class, 'print'])->name('admin.aguinaldos.print')->middleware(['auth', 'can:admin.aguinaldos.index']);
Route::get('aguinaldos/print-general', [App\Http\Controllers\Admin\AguinaldoController::class, 'printGeneral'])->name('admin.aguinaldos.print.general')->middleware(['auth', 'can:admin.aguinaldos.index']);

// Rutas para Retroactivos
Route::get('/admin/retroactivos', [App\Http\Controllers\Admin\RetroactivoController::class, 'index'])->name('admin.retroactivos.index')->middleware(['auth', 'can:admin.retroactivos.index']);
Route::get('/admin/retroactivos/create', [App\Http\Controllers\Admin\RetroactivoController::class, 'create'])->name('admin.retroactivos.create')->middleware(['auth', 'can:admin.retroactivos.create']);
Route::post('/admin/retroactivos', [App\Http\Controllers\Admin\RetroactivoController::class, 'store'])->name('admin.retroactivos.store')->middleware(['auth', 'can:admin.retroactivos.create']);
Route::post('/admin/retroactivos/calcular', [App\Http\Controllers\Admin\RetroactivoController::class, 'calcularMasivo'])->name('admin.retroactivos.calcular')->middleware(['auth', 'can:admin.retroactivos.create']);
Route::get('/admin/retroactivos/print-general', [App\Http\Controllers\Admin\RetroactivoController::class, 'printGeneral'])->name('admin.retroactivos.print.general')->middleware(['auth', 'can:admin.retroactivos.index']);
Route::get('/admin/retroactivos/{id}/edit', [App\Http\Controllers\Admin\RetroactivoController::class, 'edit'])->name('admin.retroactivos.edit')->middleware(['auth', 'can:admin.retroactivos.edit']);
Route::put('/admin/retroactivos/{id}', [App\Http\Controllers\Admin\RetroactivoController::class, 'update'])->name('admin.retroactivos.update')->middleware(['auth', 'can:admin.retroactivos.edit']);
Route::get('/admin/retroactivos/{id}', [App\Http\Controllers\Admin\RetroactivoController::class, 'show'])->name('admin.retroactivos.show')->middleware(['auth', 'can:admin.retroactivos.index']);
Route::delete('/admin/retroactivos/{id}', [App\Http\Controllers\Admin\RetroactivoController::class, 'destroy'])->name('admin.retroactivos.destroy')->middleware(['auth', 'can:admin.retroactivos.destroy']);
Route::get('/admin/retroactivos/{id}/print', [App\Http\Controllers\Admin\RetroactivoController::class, 'print'])->name('admin.retroactivos.print')->middleware(['auth', 'can:admin.retroactivos.index']);

// Rutas para Contratos
Route::get('/admin/contratos', [App\Http\Controllers\Admin\ContratoController::class, 'index'])->name('admin.contratos.index')->middleware(['auth', 'can:admin.contratos.index']);
Route::get('/admin/contratos/create', [App\Http\Controllers\Admin\ContratoController::class, 'create'])->name('admin.contratos.create')->middleware(['auth', 'can:admin.contratos.create']);
Route::post('/admin/contratos', [App\Http\Controllers\Admin\ContratoController::class, 'store'])->name('admin.contratos.store')->middleware(['auth', 'can:admin.contratos.create']);
Route::get('/admin/contratos/{id}/edit', [App\Http\Controllers\Admin\ContratoController::class, 'edit'])->name('admin.contratos.edit')->middleware(['auth', 'can:admin.contratos.edit']);
Route::put('/admin/contratos/{id}', [App\Http\Controllers\Admin\ContratoController::class, 'update'])->name('admin.contratos.update')->middleware(['auth', 'can:admin.contratos.edit']);
Route::get('/admin/contratos/{id}', [App\Http\Controllers\Admin\ContratoController::class, 'show'])->name('admin.contratos.show')->middleware(['auth', 'can:admin.contratos.index']);
Route::delete('/admin/contratos/{id}', [App\Http\Controllers\Admin\ContratoController::class, 'destroy'])->name('admin.contratos.destroy')->middleware(['auth', 'can:admin.contratos.destroy']);
Route::get('/admin/contratos/{id}/imprimir', [App\Http\Controllers\Admin\ContratoController::class, 'imprimir'])->name('admin.contratos.imprimir')->middleware(['auth', 'can:admin.contratos.index']);
Route::get('/admin/contratos/{id}/download-pdf', [App\Http\Controllers\Admin\ContratoController::class, 'downloadPdf'])->name('admin.contratos.download-pdf')->middleware(['auth', 'can:admin.contratos.index']);




require __DIR__.'/settings.php';
