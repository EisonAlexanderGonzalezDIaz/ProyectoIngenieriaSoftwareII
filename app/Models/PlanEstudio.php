<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanEstudio extends Model
{
    use HasFactory;

    protected $table = 'plan_estudio';

    protected $fillable = [
        'curso_id',
        'materia_id',
        'periodo',
        'intensidad_horaria',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function materia()
    {
        return $this->belongsTo(Subject::class, 'materia_id');
    }
}
