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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
