<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'empleado'.
 * Esta tabla gestiona el personal de los centros deportivos. Incluye los campos 
 * necesarios para la autenticación (email, password, rememberToken) y la 
 * autorización mediante roles definidos por un ENUM.
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * * Define la estructura de la tabla 'empleado':
     * - `id_empleado`: Identificador único (Primary Key).
     * - `id_gimnasio`: Relación con el gimnasio de pertenencia (Foreign Key).
     * - `DNI` y `email`: Campos con restricción de unicidad para identificación.
     * - `rol`: Define el nivel de acceso (Administrador, Monitor, Limpieza).
     * - `activo`: Estado para habilitar o deshabilitar el acceso al sistema.
     * * @return void
     */
    public function up(): void
    {
        Schema::create('empleado', function (Blueprint $table) {
            $table->increments('id_empleado');
            $table->unsignedInteger('id_gimnasio');
            $table->string('nombre',100);
            $table->string('apellidos',150);
            $table->string('DNI',20)->unique();
            $table->string('email',150)->unique();
            $table->string('telefono',20);
            $table->string('password',255);
            $table->enum('rol',['Administrador','Monitor','Limpieza']);
            $table->boolean('activo')->default(true);
            $table->rememberToken(); 
            $table->timestamps();

            $table->index('id_gimnasio');
            $table->foreign('id_gimnasio')->references('id_gimnasio')->on('gimnasio')
                  ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Revierte las operaciones de migración.
     * * Elimina la tabla 'empleado' respetando el orden de integridad.
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado');
    }
};
