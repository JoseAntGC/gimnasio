<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la creación de la tabla 'usuarios'.
 * Esta tabla gestiona a los socios o clientes finales de los gimnasios.
 * A diferencia de los empleados, estos cuentan con una 'categoria' deportiva
 * que condiciona el acceso a recursos específicos (rutinas).
 */
return new class extends Migration
{
    /**
     * Ejecuta las operaciones de migración.
     * Define la estructura de la tabla 'usuarios':
     * - `id_usuario`: Identificador único del socio.
     * - `id_gimnasio`: Relación con el centro donde el socio está inscrito.
     * - `categoria`: Nivel de entrenamiento (afecta a la lógica de descarga de archivos).
     * - `DNI` y `email`: Garantizan la identidad única del socio en el sistema.
     * - `rememberToken`: Soporta la persistencia de sesión en el portal del usuario.
     * * @return void
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->unsignedInteger('id_gimnasio');
            $table->string('nombre',100);
            $table->string('apellidos',150);
            $table->string('DNI',20)->unique();
            $table->string('email',150)->unique();
            $table->string('telefono',20)->nullable();
            $table->string('password',255);
            $table->boolean('activo')->default(true);
            $table->enum('categoria', ['Principiante','Intermedio','Experto'])
                  ->default('Principiante');
            $table->rememberToken();
            $table->timestamps();
            $table->index('id_gimnasio');
            $table->foreign('id_gimnasio')
                  ->references('id_gimnasio')->on('gimnasio')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }
 
    /**
     * Revierte las operaciones de migración.
     * Elimina la tabla 'usuarios' de la base de datos.
     * * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
