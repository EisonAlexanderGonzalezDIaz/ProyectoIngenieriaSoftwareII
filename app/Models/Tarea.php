<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'materia_id',
        'docente_id',
        'titulo',
        'descripcion',
        'fecha_entrega',
        'archivo_url',
    ];

    public function materia()
    {
        return $this->belongsTo(Subject::class, 'materia_id');
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'tarea_id');
    }
}
