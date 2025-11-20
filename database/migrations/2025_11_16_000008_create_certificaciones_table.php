<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('certificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id')->index();
            $table->string('tipo')->nullable();
            $table->string('estado')->default('solicitado');
            $table->string('archivo_url')->nullable();
            $table->dateTime('fecha_solicitud')->nullable();
            $table->dateTime('fecha_emision')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificaciones');
    }
};
