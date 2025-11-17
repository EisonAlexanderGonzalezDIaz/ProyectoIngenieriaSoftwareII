<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id',   // FK al rol
        'curso_id',   // FK al curso (si aplica)
        'activo',     // 1 / 0 para estado
    ];

    /**
     * Atributos ocultos para arrays / JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts nativos de Laravel.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo'            => 'boolean',
    ];

    // =========================================================
    //  RELACIONES
    // =========================================================

    /**
     * Relación con RolesModel usando 'roles_id'.
     * Esto permite usar tanto $user->role como $user->rol.
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\RolesModel::class, 'roles_id');
    }

    public function rol()
    {
        return $this->belongsTo(\App\Models\RolesModel::class, 'roles_id');
    }

    /**
     * Relación con Curso (si el usuario es Estudiante).
     */
    public function curso()
    {
        return $this->belongsTo(\App\Models\Curso::class, 'curso_id');
    }

    /**
     * Estudiante → sus acudientes (padre, madre, tutor...).
     * Usa la tabla pivote 'acudiente_estudiante'.
     */
    public function acudientes()
    {
        return $this->belongsToMany(
            self::class,
            'acudiente_estudiante',
            'estudiante_id', // este usuario es el estudiante
            'acudiente_id'   // este usuario es el acudiente
        );
    }

    /**
     * Acudiente → estudiantes a cargo.
     */
    public function estudiantesACargo()
    {
        return $this->belongsToMany(
            self::class,
            'acudiente_estudiante',
            'acudiente_id',  // este usuario es el acudiente
            'estudiante_id'  // este usuario es el estudiante
        );
    }
}
