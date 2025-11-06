<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matricula_id')->nullable()->index();
            $table->unsignedBigInteger('acudiente_id')->nullable()->index();
            $table->decimal('monto', 12, 2)->default(0);
            $table->string('tipo')->nullable();
            $table->string('estado')->default('pendiente');
            $table->string('metodo')->nullable();
            $table->text('descripcion')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            // claves foráneas opcionales (no obligatorias para evitar errores en instalaciones existentes)
            // Si las tablas existen en el proyecto, se podrían activar las FK.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
};
