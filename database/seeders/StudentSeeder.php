<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\AcademicScore;
use App\Models\InterestSurvey;
use App\Models\InterviewScore;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'name' => 'Ahmad Rizki',
                'nisn' => '1234567890',
                'class' => 'IX-A',
                'gender' => 'L',
                'academic' => [
                    'mathematics' => 85,
                    'indonesian' => 78,
                    'english' => 82,
                    'science' => 88,
                    'social_studies' => 75
                ],
                'interest' => [
                    'answers' => [5, 4, 5, 3, 4, 2, 5, 3, 4, 2],
                    'total_score' => 37
                ],
                'interview' => [
                    'communication_skill' => 4,
                    'motivation' => 5,
                    'personality' => 4,
                    'academic_potential' => 5,
                    'career_orientation' => 4
                ]
            ],
            [
                'name' => 'Siti Nurhaliza',
                'nisn' => '1234567891',
                'class' => 'IX-B',
                'gender' => 'P',
                'academic' => [
                    'mathematics' => 78,
                    'indonesian' => 92,
                    'english' => 88,
                    'science' => 75,
                    'social_studies' => 90
                ],
                'interest' => [
                    'answers' => [2, 3, 2, 5, 3, 5, 2, 5, 3, 5],
                    'total_score' => 35
                ],
                'interview' => [
                    'communication_skill' => 5,
                    'motivation' => 4,
                    'personality' => 5,
                    'academic_potential' => 4,
                    'career_orientation' => 5
                ]
            ],
            [
                'name' => 'Budi Santoso',
                'nisn' => '1234567892',
                'class' => 'IX-A',
                'gender' => 'L',
                'academic' => [
                    'mathematics' => 90,
                    'indonesian' => 80,
                    'english' => 75,
                    'science' => 92,
                    'social_studies' => 70
                ],
                'interest' => [
                    'answers' => [5, 5, 4, 2, 5, 1, 5, 2, 5, 1],
                    'total_score' => 35
                ],
                'interview' => [
                    'communication_skill' => 3,
                    'motivation' => 5,
                    'personality' => 4,
                    'academic_potential' => 5,
                    'career_orientation' => 3
                ]
            ],
            [
                'name' => 'Dewi Kartika',
                'nisn' => '1234567893',
                'class' => 'IX-C',
                'gender' => 'P',
                'academic' => [
                    'mathematics' => 70,
                    'indonesian' => 88,
                    'english' => 90,
                    'science' => 72,
                    'social_studies' => 95
                ],
                'interest' => [
                    'answers' => [1, 2, 1, 5, 2, 5, 1, 5, 2, 5],
                    'total_score' => 33
                ],
                'interview' => [
                    'communication_skill' => 5,
                    'motivation' => 4,
                    'personality' => 5,
                    'academic_potential' => 3,
                    'career_orientation' => 5
                ]
            ],
            [
                'name' => 'Fajar Nugroho',
                'nisn' => '1234567894',
                'class' => 'IX-B',
                'gender' => 'L',
                'academic' => [
                    'mathematics' => 88,
                    'indonesian' => 75,
                    'english' => 80,
                    'science' => 90,
                    'social_studies' => 78
                ],
                'interest' => [
                    'answers' => [4, 5, 4, 3, 4, 2, 4, 3, 4, 2],
                    'total_score' => 36
                ],
                'interview' => [
                    'communication_skill' => 4,
                    'motivation' => 4,
                    'personality' => 4,
                    'academic_potential' => 4,
                    'career_orientation' => 4
                ]
            ]
        ];

        foreach ($students as $studentData) {
            $student = Student::create([
                'name' => $studentData['name'],
                'nisn' => $studentData['nisn'],
                'class' => $studentData['class'],
                'gender' => $studentData['gender']
            ]);

            // Create academic score
            AcademicScore::create([
                'student_id' => $student->id,
                'mathematics' => $studentData['academic']['mathematics'],
                'indonesian' => $studentData['academic']['indonesian'],
                'english' => $studentData['academic']['english'],
                'science' => $studentData['academic']['science'],
                'social_studies' => $studentData['academic']['social_studies']
            ]);

            // Create interest survey
            InterestSurvey::create([
                'student_id' => $student->id,
                'answers' => $studentData['interest']['answers'],
                'total_score' => $studentData['interest']['total_score']
            ]);

            // Create interview score
            $interviewData = $studentData['interview'];
            InterviewScore::create([
                'student_id' => $student->id,
                'communication_skill' => $interviewData['communication_skill'],
                'motivation' => $interviewData['motivation'],
                'personality' => $interviewData['personality'],
                'academic_potential' => $interviewData['academic_potential'],
                'career_orientation' => $interviewData['career_orientation'],
                'total_score' => array_sum($interviewData)
            ]);
        }
    }
}
