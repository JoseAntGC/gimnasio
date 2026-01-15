<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GimnasioSeeder::class,   // crea gimnasios
            EmpleadoSeeder::class,   // empleados referencian gimnasio
            UsuarioSeeder::class,    // usuarios sin FKs por ahora
            ServicioSeeder::class,   // servicios referencian gimnasio
            PlanSeeder::class,        // planes referencian servicio
            SuscripcionSeeder::class,// suscripciones usan usuario + gimnasio
            AsignacionSeeder::class, // asignaciones usan empleado + servicio
        ]);
    }
}
