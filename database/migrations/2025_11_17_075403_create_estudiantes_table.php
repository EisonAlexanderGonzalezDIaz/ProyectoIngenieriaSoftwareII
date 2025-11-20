<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();

            // Nombre completo del estudiante
            $table->string('nombre');

            // Documento o identificación (puede ser opcional por ahora)
            $table->string('identificacion')->nullable();

            // Relación con la tabla cursos (colegio.cursos)
            $table->unsignedBigInteger('curso_id')->nullable();

            $table->timestamps();

            // Clave foránea a cursos.id
            $table->foreign('curso_id')
                  ->references('id')->on('cursos')
                  ->nullOnDelete(); // si se borra el curso → curso_id = NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
    