<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nisn',
        'class',
        'gender'
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
}
