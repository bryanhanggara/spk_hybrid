<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nisn',
        'class',
        'gender',
        'major_choice_1',
        'major_choice_2'
    ];

    public function academicScore(): HasOne
    {
        return $this->hasOne(AcademicScore::class);
    }

    public function interestSurvey(): HasOne
    {
        return $this->hasOne(InterestSurvey::class);
    }

    public function interviewScore(): HasOne
    {
        return $this->hasOne(InterviewScore::class);
    }

    public function spkResults(): HasMany
    {
        return $this->hasMany(SpkResult::class);
    }

    /**
     * Get test answers for this student
     */
    public function testAnswers(): HasMany
    {
        return $this->hasMany(\App\Models\StudentTestAnswer::class);
    }

    /**
     * Get the password for authentication (not used, but required by Authenticatable)
     */
    public function getAuthPassword()
    {
        return null; // We don't use passwords for student authentication
    }
}
