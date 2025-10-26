<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterestSurvey extends Model
{
    protected $fillable = [
        'student_id',
        'answers',
        'total_score'
    ];

    protected $casts = [
        'answers' => 'array',
        'total_score' => 'decimal:2'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
