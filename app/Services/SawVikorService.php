<?php

namespace App\Services;

use App\Models\Student;
use App\Models\SpkResult;

class SawVikorService
{
    /**
     * Bobot untuk setiap kriteria
     */
    private array $weights = [
        'academic' => 0.4,      // 40% untuk nilai akademik
        'interest' => 0.3,       // 30% untuk angket minat
        'interview' => 0.3       // 30% untuk wawancara
    ];

    /**
     * Kriteria untuk jurusan IPA
     */
    private array $ipaCriteria = [
        'mathematics' => 0.3,
        'science' => 0.3,
        'english' => 0.2,
        'indonesian' => 0.1,
        'social_studies' => 0.1
    ];

    /**
     * Kriteria untuk jurusan IPS
     */
    private array $ipsCriteria = [
        'social_studies' => 0.3,
        'indonesian' => 0.3,
        'english' => 0.2,
        'mathematics' => 0.1,
        'science' => 0.1
    ];

    /**
     * Menghitung rekomendasi jurusan menggunakan SAW-VIKOR
     */
    public function calculateRecommendation(Student $student): array
    {
        // Ambil data siswa
        $academicScore = $student->academicScore;
        $interestSurvey = $student->interestSurvey;
        $interviewScore = $student->interviewScore;

        if (!$academicScore || !$interestSurvey || !$interviewScore) {
            throw new \Exception('Data siswa tidak lengkap');
        }

        // Normalisasi data menggunakan SAW
        $normalizedData = $this->normalizeData($academicScore, $interestSurvey, $interviewScore);

        // Hitung skor untuk setiap jurusan
        $ipaScore = $this->calculateMajorScore($normalizedData, 'IPA');
        $ipsScore = $this->calculateMajorScore($normalizedData, 'IPS');

        // Gunakan VIKOR untuk perangkingan
        $vikorResults = $this->calculateVikor($ipaScore, $ipsScore);

        return [
            'student_id' => $student->id,
            'recommended_major' => $vikorResults['recommended_major'],
            'saw_score' => $vikorResults['saw_score'],
            'vikor_score' => $vikorResults['vikor_score'],
            'final_score' => $vikorResults['final_score'],
            'calculation_details' => $vikorResults['details']
        ];
    }

    /**
     * Normalisasi data menggunakan metode SAW
     */
    private function normalizeData($academicScore, $interestSurvey, $interviewScore): array
    {
        // Normalisasi nilai akademik (0-100 -> 0-1)
        $normalizedAcademic = [
            'mathematics' => $academicScore->mathematics / 100,
            'indonesian' => $academicScore->indonesian / 100,
            'english' => $academicScore->english / 100,
            'science' => $academicScore->science / 100,
            'social_studies' => $academicScore->social_studies / 100
        ];

        // Normalisasi angket minat (0-100 -> 0-1)
        $normalizedInterest = $interestSurvey->total_score / 100;

        // Normalisasi wawancara (0-25 -> 0-1)
        $normalizedInterview = $interviewScore->total_score / 25;

        return [
            'academic' => $normalizedAcademic,
            'interest' => $normalizedInterest,
            'interview' => $normalizedInterview
        ];
    }

    /**
     * Hitung skor untuk jurusan tertentu
     */
    private function calculateMajorScore(array $normalizedData, string $major): float
    {
        $criteria = $major === 'IPA' ? $this->ipaCriteria : $this->ipsCriteria;
        
        $academicScore = 0;
        foreach ($criteria as $subject => $weight) {
            $academicScore += $normalizedData['academic'][$subject] * $weight;
        }

        // Hitung skor akhir dengan bobot
        $finalScore = (
            $academicScore * $this->weights['academic'] +
            $normalizedData['interest'] * $this->weights['interest'] +
            $normalizedData['interview'] * $this->weights['interview']
        );

        return $finalScore;
    }

    /**
     * Implementasi algoritma VIKOR
     */
    private function calculateVikor(float $ipaScore, float $ipsScore): array
    {
        $scores = [
            'IPA' => $ipaScore,
            'IPS' => $ipsScore
        ];

        // Hitung nilai maksimum dan minimum
        $maxScore = max($scores);
        $minScore = min($scores);

        // Hitung S (utility measure) dan R (regret measure)
        $s = [];
        $r = [];
        
        foreach ($scores as $major => $score) {
            // S = (max - score) / (max - min)
            $s[$major] = ($maxScore - $score) / ($maxScore - $minScore);
            
            // R = max dari semua kriteria (dalam hal ini hanya satu nilai)
            $r[$major] = $s[$major];
        }

        // Hitung Q (compromise measure) dengan v = 0.5
        $v = 0.5;
        $q = [];
        foreach ($scores as $major => $score) {
            $q[$major] = $v * $s[$major] + (1 - $v) * $r[$major];
        }

        // Tentukan rekomendasi berdasarkan Q terendah
        $recommendedMajor = array_keys($q, min($q))[0];
        $vikorScore = min($q);

        return [
            'recommended_major' => $recommendedMajor,
            'saw_score' => $scores[$recommendedMajor],
            'vikor_score' => $vikorScore,
            'final_score' => $scores[$recommendedMajor] * (1 - $vikorScore),
            'details' => [
                'scores' => $scores,
                's_values' => $s,
                'r_values' => $r,
                'q_values' => $q,
                'max_score' => $maxScore,
                'min_score' => $minScore
            ]
        ];
    }

    /**
     * Hitung peringkat untuk semua siswa
     */
    public function calculateRankings(): array
    {
        $students = Student::with(['academicScore', 'interestSurvey', 'interviewScore'])->get();
        $results = [];

        foreach ($students as $student) {
            try {
                $result = $this->calculateRecommendation($student);
                $results[] = $result;
            } catch (\Exception $e) {
                continue; // Skip siswa dengan data tidak lengkap
            }
        }

        // Urutkan berdasarkan final_score
        usort($results, function($a, $b) {
            return $b['final_score'] <=> $a['final_score'];
        });

        // Tambahkan peringkat
        foreach ($results as $index => &$result) {
            $result['rank'] = $index + 1;
        }

        return $results;
    }
}
