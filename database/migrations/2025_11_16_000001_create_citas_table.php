<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id')->index();
            $table->unsignedBigInteger('orientador_id')->nullable()->index();
            $table->dateTime('fecha')->nullable();
            $table->string('hora')->nullable();
            $table->text('motivo')->nullable();
            $table->string('estado')->default('solicitado');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
};
