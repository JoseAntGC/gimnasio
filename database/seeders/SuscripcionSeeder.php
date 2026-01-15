<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuscripcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suscripcion')->insert([
            [
                'id_usuario'  => 1,
                'id_gimnasio' => 1,
                'id_plan'     => 3, // maquinas+clases
                'precio'      => 39.90,
                'fecha_alta'  => '2025-01-10',
                'fecha_baja'  => null,
                'activa'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_usuario'  => 2,
                'id_gimnasio' => 1,
                'id_plan'     => 5, // plan completo
                'precio'      => 49.90,
                'fecha_alta'  => '2025-02-01',
                'fecha_baja'  => null,
                'activa'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_usuario'  => 3,
                'id_gimnasio' => 2,
                'id_plan'     => 4, // natación
                'precio'      => 29.90,
                'fecha_alta'  => '2025-01-15',
                'fecha_baja'  => null,
                'activa'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_usuario'  => 4,
                'id_gimnasio' => 2,
                'id_plan'     => 1, // maquinas
                'precio'      => 25.00,
                'fecha_alta'  => '2024-11-01',
                'fecha_baja'  => '2025-01-31',
                'activa'      => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}