<?php 

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            // ===== Gimnasio 1 =====
            [
                'id_gimnasio' => 1,
                'nombre'      => 'Carlos',
                'apellidos'   => 'Pérez López',
                'DNI'         => '11111111A',
                'email'       => 'carlos@gym.test',
                'telefono'    => '600111111',
                'password'    => Hash::make('123456'),
                'activo'      => 1,
                'categoria'   => 'Principiante',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 1,
                'nombre'      => 'Lucía',
                'apellidos'   => 'Gómez Ruiz',
                'DNI'         => '22222222B',
                'email'       => 'lucia@gym.test',
                'telefono'    => '600222222',
                'password'    => Hash::make('123456'),
                'activo'      => 1,
                'categoria'   => 'Intermedio',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 1,
                'nombre'      => 'Iván',
                'apellidos'   => 'Sánchez Díaz',
                'DNI'         => '33333333C',
                'email'       => 'ivan@gym.test',
                'telefono'    => '600333333',
                'password'    => Hash::make('123456'),
                'activo'      => 0,
                'categoria'   => 'Experto',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // ===== Gimnasio 2 =====
            [
                'id_gimnasio' => 2,
                'nombre'      => 'María',
                'apellidos'   => 'Navarro Torres',
                'DNI'         => '44444444D',
                'email'       => 'maria@gym.test',
                'telefono'    => '600444444',
                'password'    => Hash::make('123456'),
                'activo'      => 1,
                'categoria'   => 'Principiante',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 2,
                'nombre'      => 'Javier',
                'apellidos'   => 'Moreno Cano',
                'DNI'         => '55555555E',
                'email'       => 'javier@gym.test',
                'telefono'    => '600555555',
                'password'    => Hash::make('123456'),
                'activo'      => 1,
                'categoria'   => 'Experto',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
