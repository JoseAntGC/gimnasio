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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};