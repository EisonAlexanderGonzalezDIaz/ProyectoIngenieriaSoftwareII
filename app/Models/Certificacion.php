<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificacion extends Model
{
    use HasFactory;

    protected $table = 'certificaciones';

    protected $fillable = [
        'estudiante_id',
        'tipo', // certificado de notas, certificado de conducta, etc
        'estado', // solicitado, procesando, listo, descargado
        'archivo_url',
        'fecha_solicitud',
        'fecha_emision',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
}
