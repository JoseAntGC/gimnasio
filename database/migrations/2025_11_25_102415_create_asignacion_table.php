<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'asignacion'.
 * * Esta tabla gestiona el horario operativo y la asignación de recursos humanos.
 * Define qué empleado es responsable de qué servicio, en qué día de la semana 
 * y a qué hora específica.
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * * Define la estructura de la tabla 'asignacion':
     * - `id_asignacion`: Identificador único del registro de horario.
     * - `id_servicio`: Referencia a la actividad (ej. Yoga, CrossFit).
     * - `id_empleado`: Referencia al monitor o instructor responsable.
     * - `dia`: Enumeración de los días de la semana para el horario recurrente.
     * - `hora`: Momento del inicio de la actividad.
     * * Restricciones:
     * - **Unique Composite**: Evita la duplicidad de un mismo empleado impartiendo 
     * el mismo servicio en el mismo horario y día.
     * - **Foreign Keys**: Garantiza que no se eliminen empleados o servicios con 
     * horarios asignados.
     * * @return void
     */
    public function up(): void
    {
        Schema::create('asignacion', function (Blueprint $table) {
            $table->increments('id_asignacion');
            $table->unsignedInteger('id_servicio');
            $table->unsignedInteger('id_empleado');
            $table->enum('dia',['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo']);
            $table->time('hora');
            $table->timestamps();

            $table->unique(['id_empleado','id_servicio','dia','hora']);
            $table->foreign('id_empleado')->references('id_empleado')->on('empleado')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_servicio')->references('id_servicio')->on('servicio')
                  ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Revierte las operaciones de migración.
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion');
    }
};
