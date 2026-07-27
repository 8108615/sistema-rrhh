<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\Area;
use App\Models\Departamento;
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
    }
}
