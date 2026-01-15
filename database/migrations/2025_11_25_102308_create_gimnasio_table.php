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
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gimnasio');
    }
};
