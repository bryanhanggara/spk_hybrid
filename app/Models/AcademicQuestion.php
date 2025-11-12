<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'points',
        'order',
        'is_active'
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get subject name in Indonesian
     */
    public function getSubjectNameAttribute(): string
    {
        return match($this->subject) {
            'mathematics' => 'Matematika',
            'indonesian' => 'Bahasa Indonesia',
            'english' => 'Bahasa Inggris',
            'science' => 'IPA',
            'social_studies' => 'IPS',
            default => $this->subject
        };
    }

    /**
     * Get all answers for this question
     */
    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentTestAnswer::class, 'question_id');
    }

    /**
     * Scope untuk filter berdasarkan subject
     */
    public function scopeForSubject($query, string $subject)
    {
        return $query->where('subject', $subject);
    }

    /**
     * Scope untuk hanya soal aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk urut berdasarkan order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }
}

