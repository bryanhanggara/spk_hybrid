<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpkResult extends Model
{
    protected $fillable = [
        'student_id',
        'recommended_major',
        'saw_score',
        'vikor_score',
        'final_score',
        'rank',
        'calculation_details'
    ];

    protected $casts = [
        'saw_score' => 'decimal:4',
        'vikor_score' => 'decimal:4',
        'final_score' => 'decimal:4',
        'calculation_details' => 'array'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
