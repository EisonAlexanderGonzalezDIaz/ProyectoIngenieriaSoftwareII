<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * Table name (migrations use Spanish `materias`).
     */
    protected $table = 'materias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'credits',
        'department',
    ];

    /**
     * The teachers that belong to the subject.
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'docente_materia_curso', 'subject_id', 'docente_id');
    }
}
