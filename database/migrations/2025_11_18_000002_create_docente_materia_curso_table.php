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
        // Tabla pivote: Docente dicta Materia en Curso
        if (!Schema::hasTable('docente_materia_curso')) {
            Schema::create('docente_materia_curso', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('docente_id'); // User con rol Docente
                $table->unsignedBigInteger('subject_id');  // Materia (de tabla subjects)
                $table->unsignedBigInteger('curso_id');    // Curso
                $table->timestamps();

                $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
                
                $table->unique(['docente_id', 'subject_id', 'curso_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docente_materia_curso');
    }
};

