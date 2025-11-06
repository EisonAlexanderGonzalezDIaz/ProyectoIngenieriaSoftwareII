<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'specialty',
        'department',
        'degree',
        'university',
        'experience',
        'status',
    ];

    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The subjects that belong to the teacher.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    /**
     * Get the performance evaluations for the teacher.
     */
    public function performanceEvaluations()
    {
        return $this->hasMany(PerformanceEvaluation::class);
    }
}