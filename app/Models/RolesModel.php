<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model
{
    protected $table = 'roles';

    protected $fillable = ['nombre', 'descripcion', 'permisos'];

    protected $casts = [
        'permisos' => 'array',
    ];

    public $timestamps = true;

    public function usuarios()
    {
        return $this->hasMany(User::class, 'roles_id');
    }

    public function tienePermiso($permiso)
    {
        return in_array($permiso, $this->permisos ?? []);
    }

    public static function obtenerRolesSistema()
    {
        return [
            'Rector' => 'Rector',
            'Estudiante' => 'Estudiante',
            'Docente' => 'Docente',
            'Acudiente' => 'Acudiente',
            'Coordinador Academico' => 'Coordinador Academico',
            'Coodinador Disciplinario' => 'Coodinador Disciplinario',
            'Orientador' => 'Orientador',
            'Tesorero' => 'Tesorero',
            'Administrador Sistema' => 'Administrador Sistema',
        ];
    }
}
