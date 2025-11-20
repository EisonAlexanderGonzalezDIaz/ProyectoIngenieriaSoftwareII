<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteDisciplinario extends Model
{
    use HasFactory;

    protected $table = 'reportes_disciplinarios';

    protected $fillable = [
        'estudiante_id',
        'docente_id',
        'fecha',
        'descripcion',
        'tipo_falta', // leve, grave, muy grave
        'sancion',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }
}
