<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RolesModel;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atributos que se pueden asignar en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id',
    ];

    /**
     * Atributos que deben ocultarse al serializar.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que deben ser casteados.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con el modelo RolesModel
     * (cada usuario pertenece a un rol)
     */
    public function rol()
    {
        return $this->belongsTo(RolesModel::class, 'roles_id');
    }

    // Estudiante → sus acudientes
    public function acudientes()
    {
    return $this->belongsToMany(
        self::class,
        'acudiente_estudiante',
        'estudiante_id',
        'acudiente_id'
    );
    }

    // Acudiente → estudiantes a cargo
    public function estudiantesACargo()
    {
    return $this->belongsToMany(
        self::class,
        'acudiente_estudiante',
        'acudiente_id',
        'estudiante_id'
    );
    }


    /**
     * Métodos auxiliares para verificar el rol del usuario
     */

    public function isAdminSistema(): bool
    {
        return $this->rol && $this->rol->nombre === 'AdministradorSistema';
    }

    public function isRector(): bool
    {
        return $this->rol && $this->rol->nombre === 'Rector';
    }

    public function isCoordinadorAcademico(): bool
    {
        return $this->rol && $this->rol->nombre === 'CoordinadorAcademico';
    }

    public function isDocente(): bool
    {
        return $this->rol && $this->rol->nombre === 'Docente';
    }

    public function isCoordinadorDisciplinario(): bool
    {
        return $this->rol && $this->rol->nombre === 'CoordinadorDisciplinario';
    }

    public function isOrientador(): bool
    {
        return $this->rol && $this->rol->nombre === 'Orientador';
    }

    public function isTesoreria(): bool
    {
        return $this->rol && $this->rol->nombre === 'Tesoreria';
    }

    public function isEstudiante(): bool
    {
        return $this->rol && $this->rol->nombre === 'Estudiante';
    }

    public function curso()
    {
    // FK 'curso_id' en la tabla 'users' → tabla 'cursos'
    return $this->belongsTo(\App\Models\Curso::class, 'curso_id');
    }

    protected $casts = [
    // ...
    'activo' => 'boolean',
]   ;

}
