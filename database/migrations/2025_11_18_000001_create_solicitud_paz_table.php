<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitudes_paz', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acudiente_id');
            $table->unsignedBigInteger('estudiante_id')->nullable();
            $table->string('estado')->default('solicitado'); // solicitado, procesando, listo, rechazado
            $table->text('mensaje')->nullable();
            $table->string('archivo_url')->nullable();
            $table->dateTime('fecha_solicitud')->nullable();
            $table->dateTime('fecha_respuesta')->nullable();
            $table->timestamps();

            $table->foreign('acudiente_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('estudiante_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['acudiente_id']);
            $table->index(['estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_paz');
    }
};
