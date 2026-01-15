<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GimnasioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gimnasio')->insert([
            [
                'nombre'    => 'Gimnasio Centro Fit',
                'cif'       => 'B12345678',
                'direccion' => 'Calle Mayor 10, 28001 Madrid',
                'telefono'  => '910000001',
                'email'     => 'centrofit@gimnasio.com',
                'activo'    => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'nombre'    => 'Gimnasio Sur Wellness',
                'cif'       => 'B87654321',
                'direccion' => 'Avenida del Sur 25, 28041 Madrid',
                'telefono'  => '910000002',
                'email'     => 'surwellness@gimnasio.com',
                'activo'    => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);
    }
}

