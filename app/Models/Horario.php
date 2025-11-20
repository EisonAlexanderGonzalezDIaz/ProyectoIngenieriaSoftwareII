<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'docente_id',
        'materia_id',
        'dia',
        'hora_inicio',
        'hora_fin',
        'aula',
    ];

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function materia()
    {
        return $this->belongsTo(Subject::class, 'materia_id');
    }
}
