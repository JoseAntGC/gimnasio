<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'gimnasio'.
 * Esta tabla almacena la información base de los centros deportivos registrados
 * en el sistema. Es el eje central para la multi-tenencia (multi-gym) mediante
 * el filtrado por 'id_gimnasio' en otras entidades.
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * * Define la estructura de la tabla 'gimnasio':
     * - `id_gimnasio`: Clave primaria autoincremental.
     * - `nombre`: Denominación comercial del centro.
     * - `cif`: Identificador fiscal.
     * - `email`: Correo electrónico único para contacto o notificaciones.
     * - `activo`: Estado operativo del gimnasio (para bajas lógicas).
     * * @return void
     */
    public function up(): void
    {
        Schema::create('gimnasio', function (Blueprint $table) {
            $table->increments('id_gimnasio');
            $table->string('nombre',120);
            $table->string('cif',20);
            $table->string('direccion',200);
            $table->string('telefono',20);
            $table->string('email',150)->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Revierte las operaciones de migración.
     * Elimina la tabla 'gimnasio' de la base de datos. 
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('gimnasio');
    }
};
