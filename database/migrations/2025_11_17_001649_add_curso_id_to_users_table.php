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
        Schema::table('users', function (Blueprint $table) {
            // Agregamos la columna curso_id como llave foránea opcional a la tabla cursos
            $table->unsignedBigInteger('curso_id')
                  ->nullable()
                  ->after('roles_id'); // ajústalo si 'roles_id' no existe o quieres otro orden

            $table->foreign('curso_id')
                  ->references('id')->on('cursos')
                  ->nullOnDelete(); // si se borra el curso, curso_id queda en null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Primero eliminamos la foreign key y luego la columna
            $table->dropForeign(['curso_id']);
            $table->dropColumn('curso_id');
        });
    }
};
