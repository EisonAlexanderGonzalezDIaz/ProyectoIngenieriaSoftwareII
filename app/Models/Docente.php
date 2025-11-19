<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $fillable = [
        'nombre_completo',
        'email',
        'telefono',
        'materia',
    ];

    /**
     * Relación muchos a muchos con materias (subjects).
     * Usa la tabla pivote 'docente_materia_curso' si existe.
     */
    public function materias()
    {
        return $this->belongsToMany(Subject::class, 'docente_materia_curso', 'docente_id', 'subject_id')
                    ->withTimestamps();
    }
}
