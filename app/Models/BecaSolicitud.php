<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BecaSolicitud extends Model
{
    protected $table = 'beca_solicitudes';

    protected $fillable = [
        'acudiente_id',
        'estudiante_id',
        'tipo',
        'monto_estimado',
        'detalle',
        'estado',
        'fecha_solicitud',
        'fecha_resolucion',
    ];

    protected $casts = [
        'monto_estimado' => 'decimal:2',
        'fecha_solicitud' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    /**
     * Relationship: acudiente (User who requested the scholarship)
     */
    public function acudiente()
    {
        return $this->belongsTo(User::class, 'acudiente_id');
    }

    /**
     * Relationship: estudiante (Student who the scholarship is for)
     */
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
}
