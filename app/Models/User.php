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
     * Comprueba si el usuario tiene un rol concreto.
     * Acepta nombre de rol (string), id de rol (int) o array de nombres/ids.
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }
            return false;
        }

        // Si pasan un id numérico
        if (is_numeric($role)) {
            return intval($this->roles_id) === intval($role);
        }

        // Comparamos por nombre (caso-insensible) si el rol está cargado
        $nombre = $this->role ? $this->role->nombre : null;
        if ($nombre) {
            return mb_strtolower($nombre) === mb_strtolower($role);
        }

        return false;
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

    /**
     * Docente → materias que dicta (en relación pivot con cursos).
     */
    public function materiasDictadas()
    {
        return $this->belongsToMany(
            Subject::class,
            'docente_materia_curso',
            'docente_id',
            'materia_id'
        )->withPivot('curso_id')->withTimestamps();
    }

    /**
     * Docente → cursos en los que dicta.
     */
    public function cursosDictados()
    {
        return $this->belongsToMany(
            Curso::class,
            'docente_materia_curso',
            'docente_id',
            'curso_id'
        )->withPivot('materia_id')->withTimestamps();
    }

    /**
     * Docente → estudiantes en sus cursos.
     */
    public function estudiantesEnMisClases()
    {
        return $this->hasManyThrough(
            User::class,
            Curso::class,
            'id',
            'curso_id',
            'id',
            'id'
        )->where('roles_id', function($q) {
            // Asumir que estudiantes tienen un rol específico
            $q->select('id')->from('roles_models')->where('nombre', 'Estudiante');
        });
    }
}
