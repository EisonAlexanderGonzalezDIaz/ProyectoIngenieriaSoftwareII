<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasoDisciplinario extends Model
{
    use HasFactory;

    protected $table = 'casos_disciplinarios';

    protected $fillable = [
        'codigo',
        'estudiante_id',
        'encargado_id',
        'tipo',
        'riesgo',
        'estado',
        'descripcion',
        'fecha_apertura',
        'ultimo_seguimiento',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'ultimo_seguimiento' => 'date',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'encargado_id');
    }
}
