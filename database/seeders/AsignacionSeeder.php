<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('asignacion')->insert([
            // Marta - Gimnasio 1
            [
                'id_servicio' => 2, // Zumba (gimnasio 1)
                'id_empleado' => 2, // Marta
                'dia'         => 'Lunes',
                'hora'        => '18:00:00',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_servicio' => 2, // Zumba
                'id_empleado' => 2,
                'dia'         => 'Miercoles',
                'hora'        => '19:00:00',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_servicio' => 3, // CrossFit
                'id_empleado' => 2,
                'dia'         => 'Viernes',
                'hora'        => '20:00:00',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],

            // Laura - Gimnasio 2
            [
                'id_servicio' => 5, // Natación
                'id_empleado' => 4, // Laura
                'dia'         => 'Martes',
                'hora'        => '17:00:00',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_servicio' => 6, // Yoga
                'id_empleado' => 4,
                'dia'         => 'Jueves',
                'hora'        => '18:30:00',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}