<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'pagos'.
 * * Registra los movimientos económicos derivados de las suscripciones. 
 * Permite la trazabilidad de ingresos mediante métodos de pago y estados, 
 * asegurando que no existan cobros duplicados para un mismo periodo mensual.
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * * Estructura de la tabla 'pagos':
     * - `id_pago`: Identificador único de la transacción.
     * - `id_suscripcion`: Relación con el contrato que genera el pago.
     * - `periodo`: Fecha de control (normalmente día 1 del mes) para evitar duplicidad de cobro.
     * - `importe`: Cantidad económica percibida.
     * - `fecha_pago`: Timestamp exacto de la operación financiera.
     * - `metodo`: Canal por el cual se recibe el dinero (Efectivo, Tarjeta, Transferencia).
     * - `estado`: Situación administrativa del pago (Pagado, Pendiente, Anulado).
     * * @return void
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id_pago');

            $table->unsignedInteger('id_suscripcion');
            $table->date('periodo'); // día 1 del mes que cubre (ej: 2026-01-01)

            $table->decimal('importe', 8, 2);
            $table->dateTime('fecha_pago');

            $table->enum('metodo', ['efectivo','tarjeta','transferencia'])->default('tarjeta');
            $table->enum('estado', ['pagado','pendiente','anulado'])->default('pagado');

            $table->string('referencia', 120)->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // Evita 2 pagos “pagados” para el mismo periodo en la misma suscripción
            $table->unique(['id_suscripcion','periodo'], 'uq_pago_suscripcion_periodo');

            $table->foreign('id_suscripcion')
                ->references('id_suscripcion')->on('suscripcion') 
                ->onUpdate('cascade')->onDelete('cascade');

            $table->index(['id_suscripcion','fecha_pago']);
        });
    }

    /**
     * Revierte las operaciones de migración.
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

