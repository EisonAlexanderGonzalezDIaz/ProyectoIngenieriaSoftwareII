<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanAnual extends Model
{
    use HasFactory;

    protected $table = 'plan_anual';

    protected $fillable = [
        'anio',
        'titulo',
        'descripcion',
        'estado',
        'autor_id',
        'archivo_url',
    ];
}
