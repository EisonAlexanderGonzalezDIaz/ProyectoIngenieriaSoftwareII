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
        Schema::create('material_academico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('docente_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('archivo_url'); // Ruta del archivo en storage
            $table->enum('tipo', ['documento', 'video', 'presentacion', 'tarea', 'otro'])->default('documento');
            $table->date('fecha_publicacion');
            $table->date('fecha_vencimiento')->nullable(); // Para tareas con plazo
            $table->timestamps();

            $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
            // No forzamos FK a subjects si no existe
            // $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_academico');
    }
};
