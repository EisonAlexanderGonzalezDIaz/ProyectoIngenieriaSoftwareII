<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('boletines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id')->index();
            $table->string('periodo')->nullable();
            $table->string('archivo_url')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('boletines');
    }
};
