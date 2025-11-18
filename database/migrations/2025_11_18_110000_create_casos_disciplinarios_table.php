<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('casos_disciplinarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo')->unique();
            $table->unsignedBigInteger('estudiante_id')->nullable();
            $table->unsignedBigInteger('encargado_id')->nullable();
            $table->string('tipo')->nullable();
            $table->string('riesgo')->nullable();
            $table->string('estado')->default('Activo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_apertura')->nullable();
            $table->date('ultimo_seguimiento')->nullable();
            $table->timestamps();

            $table->index('estudiante_id');
            $table->index('encargado_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casos_disciplinarios');
    }
};
