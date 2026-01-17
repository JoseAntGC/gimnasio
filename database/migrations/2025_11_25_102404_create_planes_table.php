<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'planes'.
 * * Esta tabla define las ofertas comerciales o tipos de suscripción disponibles
 * (ej: "Plan Mensual", "Plan Anual VIP"). 
 * * **Nota de Integridad:** El campo `precio` en esta tabla actúa como el valor 
 * de referencia vigente. Al crear una suscripción, este valor debe persistirse 
 * en la tabla `suscripcion` para mantener el histórico de facturación del socio.
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * * Define la estructura de la tabla 'planes':
     * - `id_plan`: Identificador único autoincremental.
     * - `nombre`: Denominación comercial del plan.
     * - `precio`: Valor monetario actual (formato decimal para precisión financiera).
     * - `activo`: Indica si el plan está disponible para nuevas contrataciones.
     * * @return void
     */
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->increments('id_plan');
            $table->string('nombre', 100);
            $table->decimal('precio', 8, 2);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Revierte las operaciones de migración.
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
