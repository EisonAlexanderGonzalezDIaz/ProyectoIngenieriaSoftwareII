<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reportes_disciplinarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id')->index();
            $table->unsignedBigInteger('docente_id')->nullable()->index();
            $table->date('fecha')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo_falta')->nullable();
            $table->text('sancion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reportes_disciplinarios');
    }
};
