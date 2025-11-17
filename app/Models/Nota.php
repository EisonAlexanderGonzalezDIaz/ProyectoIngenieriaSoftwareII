<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';

    protected $fillable = [
        'estudiante_id',
        'materia_id',
        'periodo',
        'calificacion',
        'porcentaje',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function materia()
    {
        return $this->belongsTo(Subject::class, 'materia_id');
    }
}
