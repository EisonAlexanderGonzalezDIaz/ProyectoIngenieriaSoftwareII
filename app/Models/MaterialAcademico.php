<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialAcademico extends Model
{
    protected $table = 'material_academico';

    protected $fillable = [
        'docente_id',
        'subject_id',
        'titulo',
        'descripcion',
        'archivo_url',
        'tipo',
        'fecha_publicacion',
        'fecha_vencimiento',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
