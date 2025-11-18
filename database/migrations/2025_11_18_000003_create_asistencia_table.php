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
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('docente_id');
            $table->date('fecha');
            $table->enum('estado', ['presente', 'ausente', 'justificado'])->default('presente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('estudiante_id')->references('id')->on('users')->onDelete('cascade');
            // No forzamos FK a subjects si no existe
            // $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['estudiante_id', 'subject_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};
