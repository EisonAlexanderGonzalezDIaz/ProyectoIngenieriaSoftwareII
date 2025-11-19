<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('horarios', function (Blueprint $table) {
            // Agregar campos faltantes si no existen
            if (!Schema::hasColumn('horarios', 'grado')) {
                $table->string('grado')->nullable(); // ej: "10°", "9°"
            }
            if (!Schema::hasColumn('horarios', 'seccion')) {
                $table->string('seccion')->nullable(); // ej: "A", "B", "C"
            }
            if (!Schema::hasColumn('horarios', 'materia_nombre')) {
                $table->string('materia_nombre')->nullable(); // nombre de la materia
            }
            if (!Schema::hasColumn('horarios', 'docente_nombre')) {
                $table->string('docente_nombre')->nullable(); // nombre del docente
            }
            if (!Schema::hasColumn('horarios', 'estado')) {
                $table->string('estado')->default('Activo'); // Activo/Inactivo
            }
        });
    }

    public function down()
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropColumnIfExists('grado');
            $table->dropColumnIfExists('seccion');
            $table->dropColumnIfExists('materia_nombre');
            $table->dropColumnIfExists('docente_nombre');
            $table->dropColumnIfExists('estado');
        });
    }
};
