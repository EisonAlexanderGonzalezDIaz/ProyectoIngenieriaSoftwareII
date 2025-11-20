<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('docente_id')->nullable()->index();
            $table->unsignedBigInteger('materia_id')->nullable()->index();
            $table->string('dia')->nullable(); // Lunes, Martes, etc.
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->string('aula')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('horarios');
    }
};
