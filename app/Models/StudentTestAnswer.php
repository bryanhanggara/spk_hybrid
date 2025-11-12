<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTestAnswer extends Model
{
    protected $fillable = [
        'student_id',
        'question_id',
        'answer',
        'is_correct',
        'points_earned',
        'answered_at'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'integer',
        'answered_at' => 'datetime'
    ];

    /**
     * Get the student that owns this answer
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the question for this answer
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(AcademicQuestion::class, 'question_id');
    }
}

