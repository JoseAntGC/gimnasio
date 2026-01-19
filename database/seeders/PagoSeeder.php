<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pagos')->insert([

            // ===== Suscripción 1 (usuario 1, gimnasio 1) =====
            [
                'id_suscripcion' => 1,
                'periodo'        => '2025-01-01',
                'importe'        => 39.90,
                'fecha_pago'     => '2025-01-05 10:30:00',
                'metodo'         => 'tarjeta',
                'estado'         => 'pagado',
                'referencia'     => 'TPV-0001',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id_suscripcion' => 1,
                'periodo'        => '2025-02-01',
                'importe'        => 39.90,
                'fecha_pago'     => '2025-02-05 10:32:00',
                'metodo'         => 'tarjeta',
                'estado'         => 'pagado',
                'referencia'     => 'TPV-0002',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // ===== Suscripción 2 (usuario 2, gimnasio 1) =====
            [
                'id_suscripcion' => 2,
                'periodo'        => '2025-02-01',
                'importe'        => 49.90,
                'fecha_pago'     => '2025-02-01 09:00:00',
                'metodo'         => 'transferencia',
                'estado'         => 'pagado',
                'referencia'     => 'TR-55821',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // ===== Suscripción 3 (usuario 3, gimnasio 2) =====
            [
                'id_suscripcion' => 3,
                'periodo'        => '2025-01-01',
                'importe'        => 29.90,
                'fecha_pago'     => '2025-01-03 18:45:00',
                'metodo'         => 'efectivo',
                'estado'         => 'pagado',
                'referencia'     => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // ===== Suscripción 4 (inactiva / baja) =====
            [
                'id_suscripcion' => 4,
                'periodo'        => '2024-12-01',
                'importe'        => 25.00,
                'fecha_pago'     => '2024-12-02 12:00:00',
                'metodo'         => 'tarjeta',
                'estado'         => 'pagado',
                'referencia'     => 'TPV-0099',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
