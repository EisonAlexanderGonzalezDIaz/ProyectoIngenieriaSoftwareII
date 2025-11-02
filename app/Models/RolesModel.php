<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada
     */
    protected $table = 'roles';

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'permisos',
    ];

    /**
     * Conversión automática de JSON a array para permisos
     */
    protected $casts = [
        'permisos' => 'array',
    ];

    /**
     * Habilita timestamps (created_at y updated_at)
     */
    public $timestamps = true;

    /**
     * Relación con usuarios
     * Un rol puede tener muchos usuarios
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'roles_id');
    }

    /**
     * Verifica si el rol tiene un permiso específico
     */
    public function tienePermiso($permiso)
    {
        return in_array($permiso, $this->permisos ?? []);
    }

    /**
     * Retorna la lista de roles válidos del sistema
     */
    public static function obtenerRolesSistema()
    {
        return [
            'AdministradorSistema'     => 'Administrador del Sistema',
            'Rector'                   => 'Rector',
            'CoordinadorAcademico'     => 'Coordinador Académico',
            'CoordinadorDisciplinario' => 'Coordinador Disciplinario',
            'Docente'                  => 'Docente',
            'Estudiante'               => 'Estudiante',
            'Acudiente'                => 'Acudiente',
            'Orientador'               => 'Orientador',
            'Tesoreria'                => 'Tesorería',
        ];
    }
}

