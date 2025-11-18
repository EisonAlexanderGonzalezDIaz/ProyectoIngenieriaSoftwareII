<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformeDelCurso extends Model
{
    protected $table = 'informe_del_curso';

    protected $fillable = [
        'docente_id',
        'subject_id',
        'curso_id',
        'periodo',
        'desempeno_general',
        'fortalezas',
        'debilidades',
        'recomendaciones',
        'estudiantes_aprobados',
        'estudiantes_reprobados',
        'estudiantes_total',
        'promedio_curso',
        'fecha_generacion',
    ];

    protected $casts = [
        'fecha_generacion' => 'date',
        'promedio_curso' => 'decimal:2',
    ];

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
