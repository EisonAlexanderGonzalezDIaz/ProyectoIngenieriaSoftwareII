<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id')->index();
            $table->unsignedBigInteger('materia_id')->nullable()->index();
            $table->string('periodo')->nullable();
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->decimal('porcentaje', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas');
    }
};
