<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('servicio')->insert([
            // Servicios Gimnasio 1
            [
                'id_gimnasio' => 1,
                'nombre'      => 'Sala de máquinas',
                'descripcion' => 'Zona de musculación y cardio con máquinas.',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 1,
                'nombre'      => 'Clases de Zumba',
                'descripcion' => 'Clases dirigidas de Zumba para todos los niveles.',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 1,
                'nombre'      => 'CrossFit',
                'descripcion' => 'Entrenamientos de alta intensidad en grupo.',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Servicios Gimnasio 2
            [
                'id_gimnasio' => 2,
                'nombre'      => 'Sala de máquinas',
                'descripcion' => 'Zona de musculación y cardio con máquinas.',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 2,
                'nombre'      => 'Natación',
                'descripcion' => 'Piscina climatizada para natación libre y cursos.',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_gimnasio' => 2,
                'nombre'      => 'Yoga',
                'descripcion' => 'Clases de yoga para relajación y flexibilidad.',
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}