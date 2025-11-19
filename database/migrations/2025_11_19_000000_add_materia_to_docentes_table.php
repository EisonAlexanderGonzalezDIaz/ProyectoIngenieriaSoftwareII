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
        if (Schema::hasTable('docentes') && !Schema::hasColumn('docentes', 'materia')) {
            Schema::table('docentes', function (Blueprint $table) {
                $table->text('materia')->nullable()->after('telefono');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('docentes') && Schema::hasColumn('docentes', 'materia')) {
            Schema::table('docentes', function (Blueprint $table) {
                $table->dropColumn('materia');
            });
        }
    }
};
