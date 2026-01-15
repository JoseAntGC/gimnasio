<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('empleado')->insert([
            [
                'id_gimnasio' => 1,
                'nombre'      => 'Carlos',
                'apellidos'   => 'Pérez Gómez',
                'DNI'         => '12345678A',
                'email'       => 'carlos.admin@centrofit.com',
                'telefono'    => '910000101',
                'password'    => Hash::make('password123'),
                'rol'         => 'Administrador',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 1,
                'nombre'      => 'Marta',
                'apellidos'   => 'López Ruiz',
                'DNI'         => '23456789B',
                'email'       => 'marta.monitor@centrofit.com',
                'telefono'    => '910000102',
                'password'    => Hash::make('password123'),
                'rol'         => 'Monitor',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 2,
                'nombre'      => 'Javier',
                'apellidos'   => 'Sánchez Díaz',
                'DNI'         => '34567890C',
                'email'       => 'javier.admin@surwellness.com',
                'telefono'    => '910000201',
                'password'    => Hash::make('password123'),
                'rol'         => 'Administrador',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 2,
                'nombre'      => 'Laura',
                'apellidos'   => 'García Martín',
                'DNI'         => '45678901D',
                'email'       => 'laura.monitor@surwellness.com',
                'telefono'    => '910000202',
                'password'    => Hash::make('password123'),
                'rol'         => 'Monitor',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
