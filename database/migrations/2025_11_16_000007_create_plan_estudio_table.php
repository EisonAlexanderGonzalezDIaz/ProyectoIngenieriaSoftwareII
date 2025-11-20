<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plan_estudio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_id')->nullable()->index();
            $table->unsignedBigInteger('materia_id')->nullable()->index();
            $table->string('periodo')->nullable();
            $table->string('intensidad_horaria')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_estudio');
    }
};
