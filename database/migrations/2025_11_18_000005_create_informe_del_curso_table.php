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
        Schema::create('informe_del_curso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('docente_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('curso_id');
            $table->string('periodo'); // ej: "2025-I", "2025-II"
            $table->text('desempeno_general');
            $table->text('fortalezas');
            $table->text('debilidades');
            $table->text('recomendaciones');
            $table->integer('estudiantes_aprobados')->default(0);
            $table->integer('estudiantes_reprobados')->default(0);
            $table->integer('estudiantes_total')->default(0);
            $table->decimal('promedio_curso', 5, 2)->nullable();
            $table->date('fecha_generacion');
            $table->timestamps();

            $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
            // No forzamos FK a subjects si no existe
            // $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informe_del_curso');
    }
};
