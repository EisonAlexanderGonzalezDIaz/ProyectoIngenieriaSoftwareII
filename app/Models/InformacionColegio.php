<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionColegio extends Model
{
    use HasFactory;

    protected $table = 'informacion_colegio';

    protected $fillable = [
        'titulo',
        'contenido',
        'publicado',
        'autor_id',
        'published_at',
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'published_at' => 'datetime',
    ];
}
