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
        Schema::create('beca_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acudiente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('estudiante_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tipo'); // e.g., "Beca completa", "Beca parcial", "Descuento"
            $table->decimal('monto_estimado', 10, 2)->nullable();
            $table->text('detalle')->nullable();
            $table->string('estado')->default('solicitado'); // solicitado, en_revision, aprobado, rechazado
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beca_solicitudes');
    }
};
