<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'matricula_id',
        'acudiente_id',
        'monto',
        'tipo', // factura, pago, devolucion, beca, descuento
        'descripcion',
        'estado', // pendiente, pagado, devuelto
        'metodo',
        'meta', // json extra
    ];

    protected $casts = [
        'meta' => 'array',
        'monto' => 'decimal:2',
    ];

    public function matricula()
    {
        return $this->belongsTo(MatriculaAcudiente::class, 'matricula_id');
    }

    public function acudiente()
    {
        return $this->belongsTo(User::class, 'acudiente_id');
    }
}
