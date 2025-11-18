<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudPaz extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_paz';

    protected $fillable = [
        'acudiente_id',
        'estudiante_id',
        'estado',
        'mensaje',
        'archivo_url',
        'fecha_solicitud',
        'fecha_respuesta',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    public function acudiente()
    {
        return $this->belongsTo(User::class, 'acudiente_id');
    }

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
}
