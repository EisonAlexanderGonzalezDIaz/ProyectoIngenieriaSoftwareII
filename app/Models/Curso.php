<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'cursos';

    // Campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre'];

    /**
     * Relación: un curso tiene muchos estudiantes (usuarios).
     * Usa la FK 'curso_id' en la tabla 'users'.
     */
    public function estudiantes()
    {
        return $this->hasMany(\App\Models\User::class, 'curso_id');
    }

    public function curso()
    {
        return $this->belongsTo(\App\Models\Curso::class, 'curso_id');
    }
}