<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewScore extends Model
{
    protected $fillable = [
        'student_id',
        'communication_skill',
        'motivation',
        'personality',
        'academic_potential',
        'career_orientation',
        'total_score'
    ];

    protected $casts = [
        'total_score' => 'decimal:2'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
