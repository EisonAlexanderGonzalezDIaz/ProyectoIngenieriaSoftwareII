<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tarea_id')->index();
            $table->unsignedBigInteger('estudiante_id')->index();
            $table->string('archivo_url')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entregas');
    }
};
