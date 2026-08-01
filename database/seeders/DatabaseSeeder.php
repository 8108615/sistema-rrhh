<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\Area;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
        ]);

        User::create([
            'name' => 'Erick Fernando Morales Gil',
            'email' => 'erick@gmail.com',
            'foto_perfil' => null,
            'estado' => 'Activo',
            'password' => bcrypt('12345678'),
        ])->assignRole('SUPER ADMIN');

        Ajuste::create([
            'nombre' => 'THE GAME',
            'descripcion' => 'Productora Audiovisual',
            'direccion' => '4to anillo Doble via la guardia calle Eucalipto Nro 60',
            'telefono' => '76658532',
            'email' => 'thegame@gmail.com',
            'divisa' => 'BOB',
            'logo' => null,
            'web' => 'https://www.thegame.com',
        ]);

        Departamento::create([
            'nombre' => 'SANTA CRUZ',
            'sigla' => 'SCZ',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'LA PAZ',
            'sigla' => 'LPZ',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'COCHABAMBA',
            'sigla' => 'CBBA',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'ORURO',
            'sigla' => 'OR',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'POTOSI',
            'sigla' => 'PT',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'TARIJA',
            'sigla' => 'TJ',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'BENI',
            'sigla' => 'BN',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'SUCRE',
            'sigla' => 'SUC',
            'estado' => '1',
        ]);
        Departamento::create([
            'nombre' => 'PANDO',
            'sigla' => 'PN',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'JEFE TECNICO',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'TECNICO',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'CONTADOR',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'AUXILIAR CONTABLE',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'OPERADOR',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'ILUMINADOR',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'SONIDISTA',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'RELATOR',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'COMENTARISTA',
            'estado' => '1',
        ]);
        Area::create([
            'nombre' => 'PRODUCTOR',
            'estado' => '1',
        ]);

        Empleado::create([
            'departamento_id' => '1',
            'area_id' => '2',
            'nombre' => 'Edison',
            'apellido' => 'Chavez Aguilera',
            'ci' => '1111111',
            'fecha_nacimiento' => '1990-01-01',
            'fecha_ingreso' => '2020-01-01',
            'genero' => 'Masculino',
            'telefono' => '70000001',
            'direccion' => '10mo anillo Doble via la guardia',
            'email' => 'edison@gmail.com',
            'nro_cuenta' => '1000001',
            'banco' => 'BANCO MERCANTIL',
            'celular_referencia' => '79999991',
            'parentesco_referencia' => 'ESPOSA',
            'salario' => '5000.00',
            'estado' => '1',
        ]);

        Empleado::create([
            'departamento_id' => '1',
            'area_id' => '1',
            'nombre' => 'Daniel',
            'apellido' => 'Uria',
            'ci' => '1111112',
            'fecha_nacimiento' => '1986-01-05',
            'fecha_ingreso' => '2020-01-01',
            'genero' => 'Masculino',
            'telefono' => '70000001',
            'direccion' => 'Av Vigen de Cotoca',
            'email' => 'daniel@gmail.com',
            'nro_cuenta' => '1000002',
            'banco' => 'BANCO MERCANTIL',
            'celular_referencia' => '79999992',
            'parentesco_referencia' => 'ESPOSA',
            'salario' => '6500.00',
            'estado' => '1',
        ]);

        Empleado::create([
            'departamento_id' => '1',
            'area_id' => '2',
            'nombre' => 'Fernando',
            'apellido' => 'Morales Gil',
            'ci' => '1111113',
            'fecha_nacimiento' => '1991-12-20',
            'fecha_ingreso' => '2019-09-01',
            'genero' => 'Masculino',
            'telefono' => '70000003',
            'direccion' => 'Av Cumavi',
            'email' => 'fernando@gmail.com',
            'nro_cuenta' => '1000003',
            'banco' => 'BANCO MERCANTIL',
            'celular_referencia' => '79999993',
            'parentesco_referencia' => 'MADRE',
            'salario' => '4280.00',
            'estado' => '1',
        ]);

        Empleado::create([
            'departamento_id' => '1',
            'area_id' => '2',
            'nombre' => 'Jose Ernesto',
            'apellido' => 'Montenegro',
            'ci' => '1111114',
            'fecha_nacimiento' => '1986-01-15',
            'fecha_ingreso' => '2022-09-016',
            'genero' => 'Masculino',
            'telefono' => '70000004',
            'direccion' => 'Radial 27',
            'email' => 'jose@gmail.com',
            'nro_cuenta' => '1000004',
            'banco' => 'BANCO MERCANTIL',
            'celular_referencia' => '79999994',
            'parentesco_referencia' => 'ESPOSA',
            'salario' => '4500.00',
            'estado' => '1',
        ]);
        

    }
}
