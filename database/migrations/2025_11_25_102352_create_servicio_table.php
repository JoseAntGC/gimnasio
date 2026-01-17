<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'servicio'.
 * * Esta tabla define las actividades o beneficios (ej: "Yoga", "Piscina", "Musculación") 
 * que ofrece cada gimnasio. La estructura soporta multi-tenencia, permitiendo que 
 * distintos gimnasios tengan servicios con el mismo nombre, pero restringiendo 
 * duplicados dentro del mismo centro.
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * * Define la estructura de la tabla 'servicio':
     * - `id_servicio`: Identificador único (PK).
     * - `id_gimnasio`: Relación con la tabla padre 'gimnasio'.
     * - `nombre`: Nombre del servicio (ej. "Crossfit").
     * - `descripcion`: Detalle opcional de la actividad.
     * - `activo`: Estado de disponibilidad del servicio.
     * * Restricciones:
     * - **Unique Composite**: Evita que un mismo gimnasio registre dos veces un servicio con igual nombre.
     * - **Foreign Key**: Mantiene la integridad referencial con 'gimnasio'.
     * * @return void
     */
    public function up(): void
    {
        Schema::create('servicio', function (Blueprint $table) {
            $table->increments('id_servicio');
            $table->unsignedInteger('id_gimnasio');
            $table->string('nombre',120);
            $table->string('descripcion',255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            // Único por gimnasio
            $table->unique(['id_gimnasio','nombre']);
            $table->index('id_gimnasio');
            $table->foreign('id_gimnasio')->references('id_gimnasio')->on('gimnasio')
                  ->onUpdate('cascade')->onDelete('restrict');

        });
    }

    /**
     * Revierte las operaciones de migración.
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio');
    }
};
