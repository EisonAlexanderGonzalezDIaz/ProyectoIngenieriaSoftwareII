<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'estudiante_id',
        'orientador_id',
        'fecha',
        'hora',
        'motivo',
        'estado', // solicitado, confirmado, cancelado, completado
        'notas',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function orientador()
    {
        return $this->belongsTo(User::class, 'orientador_id');
    }
}
