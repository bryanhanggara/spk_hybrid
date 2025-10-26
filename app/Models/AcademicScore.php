<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicScore extends Model
{
    protected $fillable = [
        'student_id',
        'mathematics',
        'indonesian',
        'english',
        'science',
        'social_studies'
    ];

    protected $casts = [
        'mathematics' => 'decimal:2',
        'indonesian' => 'decimal:2',
        'english' => 'decimal:2',
        'science' => 'decimal:2',
        'social_studies' => 'decimal:2'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
