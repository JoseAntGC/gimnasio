<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planes = [
            [
                'nombre' => 'Máquinas',
                'precio' => 25.00,
                'activo' => 1,
            ],
            [
                'nombre' => 'Clases',
                'precio' => 30.00,
                'activo' => 1,
            ],
            [
                'nombre' => 'Máquinas + Clases',
                'precio' => 40.00,
                'activo' => 1,
            ],
            [
                'nombre' => 'Natación',
                'precio' => 35.00,
                'activo' => 1,
            ],
            [
                'nombre' => 'Plan Completo',
                'precio' => 50.00,
                'activo' => 1,
            ],
        ];

        foreach ($planes as $plan) {
            Plan::create($plan);
        }
    }
}
