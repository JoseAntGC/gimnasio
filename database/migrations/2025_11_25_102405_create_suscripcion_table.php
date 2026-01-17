<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'suscripcion'.
 * * Esta tabla actúa como el núcleo transaccional del sistema, vinculando a los 
 * usuarios con un plan de precios en un gimnasio específico. 
 * * **Consideración de Auditoría:** Almacena el precio pactado en el momento del alta
 * para proteger el contrato de futuras variaciones de precios en la tabla 'planes'.
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * * Define la estructura de la tabla 'suscripcion':
     * - `id_suscripcion`: Identificador único transaccional.
     * - `id_usuario`: Referencia al socio titular.
     * - `id_gimnasio`: Referencia al centro donde se formaliza el contrato.
     * - `id_plan`: Referencia al catálogo de planes.
     * - `precio`: Valor decimal capturado al momento de la creación (histórico).
     * - `fecha_alta/baja`: Periodo de vigencia del contrato.
     * - `activa`: Flag de estado para consultas rápidas de acceso.
     * * @return void
     */
    public function up(): void
    {
        Schema::create('suscripcion', function (Blueprint $table) {

        $table->increments('id_suscripcion');

        $table->unsignedInteger('id_usuario');
        $table->unsignedInteger('id_gimnasio');
        $table->unsignedInteger('id_plan');

        $table->decimal('precio', 8, 2);
        $table->date('fecha_alta');
        $table->date('fecha_baja')->nullable();
        $table->boolean('activa')->default(true);

        $table->timestamps();

        $table->index('id_usuario');
        $table->index('id_gimnasio');
        $table->index('id_plan');

        $table->foreign('id_usuario')
            ->references('id_usuario')->on('usuarios')
            ->onUpdate('cascade')->onDelete('restrict');

        $table->foreign('id_gimnasio')
            ->references('id_gimnasio')->on('gimnasio')
            ->onUpdate('cascade')->onDelete('restrict');

        $table->foreign('id_plan')
            ->references('id_plan')->on('planes')
            ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Revierte las operaciones de migración.
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};