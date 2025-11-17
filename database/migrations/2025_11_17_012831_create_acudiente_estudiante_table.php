<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acudiente_estudiante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acudiente_id');
            $table->unsignedBigInteger('estudiante_id');
            $table->timestamps();

            $table->foreign('acudiente_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('estudiante_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->unique(['acudiente_id', 'estudiante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acudiente_estudiante');
    }
};
