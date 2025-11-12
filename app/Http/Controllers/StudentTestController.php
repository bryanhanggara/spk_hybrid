<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicQuestion;
use App\Models\StudentTestAnswer;
use App\Models\AcademicScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentTestController extends Controller
{
    /**
     * Tampilkan daftar subtest yang tersedia
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        $subjects = [
            'mathematics' => [
                'name' => 'Matematika',
                'icon' => 'calculator',
                'color' => 'primary',
                'questions_count' => AcademicQuestion::forSubject('mathematics')->active()->count(),
                'completed' => $this->isSubjectCompleted($student, 'mathematics')
            ],
            'indonesian' => [
                'name' => 'Bahasa Indonesia',
                'icon' => 'book',
                'color' => 'success',
                'questions_count' => AcademicQuestion::forSubject('indonesian')->active()->count(),
                'completed' => $this->isSubjectCompleted($student, 'indonesian')
            ],
            'english' => [
                'name' => 'Bahasa Inggris',
                'icon' => 'language',
                'color' => 'info',
                'questions_count' => AcademicQuestion::forSubject('english')->active()->count(),
                'completed' => $this->isSubjectCompleted($student, 'english')
            ],
            'science' => [
                'name' => 'IPA',
                'icon' => 'flask',
                'color' => 'warning',
                'questions_count' => AcademicQuestion::forSubject('science')->active()->count(),
                'completed' => $this->isSubjectCompleted($student, 'science')
            ],
            'social_studies' => [
                'name' => 'IPS',
                'icon' => 'globe',
                'color' => 'danger',
                'questions_count' => AcademicQuestion::forSubject('social_studies')->active()->count(),
                'completed' => $this->isSubjectCompleted($student, 'social_studies')
            ]
        ];

        return view('student.test.index', compact('subjects', 'student'));
    }

    /**
     * Tampilkan halaman ujian untuk subtest tertentu
     */
    public function showTest($subject)
    {
        $student = Auth::guard('student')->user();
        
        // Validasi subject
        $validSubjects = ['mathematics', 'indonesian', 'english', 'science', 'social_studies'];
        if (!in_array($subject, $validSubjects)) {
            return redirect()->route('student.test.index')
                ->with('error', 'Subtest tidak valid');
        }

        // Ambil soal aktif untuk subtest ini
        $questions = AcademicQuestion::forSubject($subject)
            ->active()
            ->ordered()
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('student.test.index')
                ->with('error', 'Belum ada soal untuk subtest ini');
        }

        // Ambil jawaban siswa yang sudah ada (jika ada)
        $existingAnswers = StudentTestAnswer::where('student_id', $student->id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->pluck('answer', 'question_id')
            ->toArray();

        $subjectNames = [
            'mathematics' => 'Matematika',
            'indonesian' => 'Bahasa Indonesia',
            'english' => 'Bahasa Inggris',
            'science' => 'IPA',
            'social_studies' => 'IPS'
        ];

        return view('student.test.take', compact('questions', 'subject', 'subjectNames', 'existingAnswers', 'student'));
    }

    /**
     * Simpan jawaban siswa dan hitung nilai
     */
    public function submitTest(Request $request, $subject)
    {
        $student = Auth::guard('student')->user();
        
        // Validasi subject
        $validSubjects = ['mathematics', 'indonesian', 'english', 'science', 'social_studies'];
        if (!in_array($subject, $validSubjects)) {
            return redirect()->route('student.test.index')
                ->with('error', 'Subtest tidak valid');
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|in:a,b,c,d'
        ]);

        // Ambil semua soal untuk subtest ini
        $questions = AcademicQuestion::forSubject($subject)
            ->active()
            ->get()
            ->keyBy('id');

        $totalPoints = 0;
        $earnedPoints = 0;
        $correctAnswers = 0;
        $totalQuestions = $questions->count();

        DB::beginTransaction();
        try {
            foreach ($request->answers as $questionId => $answer) {
                if (!isset($questions[$questionId])) {
                    continue;
                }

                $question = $questions[$questionId];
                $totalPoints += $question->points;
                
                $isCorrect = ($answer === $question->correct_answer);
                $pointsEarned = $isCorrect ? $question->points : 0;
                
                if ($isCorrect) {
                    $correctAnswers++;
                    $earnedPoints += $pointsEarned;
                }

                // Simpan atau update jawaban
                StudentTestAnswer::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'question_id' => $questionId
                    ],
                    [
                        'answer' => $answer,
                        'is_correct' => $isCorrect,
                        'points_earned' => $pointsEarned,
                        'answered_at' => now()
                    ]
                );
            }

            // Hitung nilai (0-100)
            $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
            $score = round($score, 2);

            // Simpan atau update nilai akademik
            $academicScore = $student->academicScore;
            if ($academicScore) {
                $academicScore->update([$subject => $score]);
            } else {
                $student->academicScore()->create([
                    'mathematics' => $subject === 'mathematics' ? $score : 0,
                    'indonesian' => $subject === 'indonesian' ? $score : 0,
                    'english' => $subject === 'english' ? $score : 0,
                    'science' => $subject === 'science' ? $score : 0,
                    'social_studies' => $subject === 'social_studies' ? $score : 0,
                ]);
            }

            DB::commit();

            return redirect()->route('student.test.result', ['subject' => $subject])
                ->with('success', 'Ujian berhasil diselesaikan!')
                ->with('test_result', [
                    'score' => $score,
                    'correct' => $correctAnswers,
                    'total' => $totalQuestions,
                    'earned_points' => $earnedPoints,
                    'total_points' => $totalPoints
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan jawaban: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan hasil ujian
     */
    public function showResult($subject)
    {
        $student = Auth::guard('student')->user();
        
        $subjectNames = [
            'mathematics' => 'Matematika',
            'indonesian' => 'Bahasa Indonesia',
            'english' => 'Bahasa Inggris',
            'science' => 'IPA',
            'social_studies' => 'IPS'
        ];

        // Ambil hasil ujian terakhir
        $questions = AcademicQuestion::forSubject($subject)
            ->active()
            ->ordered()
            ->get();

        $answers = StudentTestAnswer::where('student_id', $student->id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->with('question')
            ->get()
            ->keyBy('question_id');

        $academicScore = $student->academicScore;
        $score = $academicScore ? $academicScore->$subject : 0;

        return view('student.test.result', compact('questions', 'answers', 'subject', 'subjectNames', 'score', 'student'));
    }

    /**
     * Cek apakah subtest sudah selesai
     */
    private function isSubjectCompleted($student, $subject)
    {
        $questions = AcademicQuestion::forSubject($subject)->active()->pluck('id');
        if ($questions->isEmpty()) {
            return false;
        }

        $answeredCount = StudentTestAnswer::where('student_id', $student->id)
            ->whereIn('question_id', $questions)
            ->count();

        return $answeredCount === $questions->count();
    }
}

