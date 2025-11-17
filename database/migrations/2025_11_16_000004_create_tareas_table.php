<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('materia_id')->nullable()->index();
            $table->unsignedBigInteger('docente_id')->nullable()->index();
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
            $table->string('archivo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tareas');
    }
};
