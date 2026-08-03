<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear los Roles base (si no existen para evitar duplicados con firstOrCreate)
        $rolSuperAdmin = Role::firstOrCreate(['name' => 'SUPER ADMIN', 'guard_name' => 'web']);
        $rolAdmin = Role::firstOrCreate(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        $rolAuxiliar = Role::firstOrCreate(['name' => 'AUXILIAR', 'guard_name' => 'web']);

        // 2. Definir todos los permisos del sistema agrupados por módulo
        $permisos = [
            // Roles
            'admin.roles.index', 'admin.roles.create', 'admin.roles.edit', 'admin.roles.destroy', 'admin.roles.permisos',
            // Usuarios
            'admin.usuarios.index', 'admin.usuarios.create', 'admin.usuarios.edit', 'admin.usuarios.destroy',
            // Departamentos
            'admin.departamentos.index', 'admin.departamentos.store', 'admin.departamentos.update', 'admin.departamentos.destroy',
            // Áreas
            'admin.areas.index', 'admin.areas.store', 'admin.areas.update', 'admin.areas.destroy',
            // Cargos
            'admin.cargos.index', 'admin.cargos.store', 'admin.cargos.update', 'admin.cargos.destroy',
            // Empleados
            'admin.empleados.index', 'admin.empleados.create', 'admin.empleados.edit', 'admin.empleados.destroy',
            // Planillas
            'admin.planillas.index', 'admin.planillas.create', 'admin.planillas.edit', 'admin.planillas.destroy',
            // Pagos de empleados
            'admin.pagos.index', 'admin.pagos.create', 'admin.pagos.edit', 'admin.pagos.destroy',
            // Permisos y Vacaciones
            'admin.permisos.index', 'admin.permisos.create', 'admin.permisos.edit', 'admin.permisos.destroy',
            // Finiquitos
            'admin.finiquitos.index', 'admin.finiquitos.create', 'admin.finiquitos.destroy',
            // Aguinaldos
            'admin.aguinaldos.index', 'admin.aguinaldos.create', 'admin.aguinaldos.edit', 'admin.aguinaldos.destroy',
            // Retroactivos
            'admin.retroactivos.index', 'admin.retroactivos.create', 'admin.retroactivos.edit', 'admin.retroactivos.destroy',
            // Contratos
            'admin.contratos.index', 'admin.contratos.create', 'admin.contratos.edit', 'admin.contratos.destroy',
            // Ajustes
            'admin.ajustes.index', 'admin.ajustes.store',
        ];

        // 3. Registrar los permisos en la base de datos
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // 4. Asignar ABSOLUTAMENTE TODOS los permisos al rol SUPER ADMIN
        $rolSuperAdmin->syncPermissions(Permission::all());

        // (Opcional) Puedes asignarle permisos limitados a ADMINISTRADOR o AUXILIAR aquí si lo deseas
    }
}
