<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion');
    }
};
