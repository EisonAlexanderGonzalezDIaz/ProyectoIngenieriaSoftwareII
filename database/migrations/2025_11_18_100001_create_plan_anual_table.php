<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_anual', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->year('anio')->nullable();
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('estado')->default('propuesto'); // propuesto, aprobado, publicado
            $table->unsignedBigInteger('autor_id')->nullable();
            $table->string('archivo_url')->nullable();
            $table->timestamps();

            $table->index('autor_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_anual');
    }
};
