<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    // Nombre de la tabla en BD
    protected $table = 'estudiantes';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'nombre',
        'identificacion',
        'curso_id',
    ];

    /**
     * Relación: Estudiante pertenece a un Curso.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
