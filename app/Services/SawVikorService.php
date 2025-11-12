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
     * Daftar jurusan SMK
     */
    private array $majors = [
        'TKR' => 'Teknik Kendaraan Ringan',
        'TSM' => 'Teknik Sepeda Motor',
        'TKJ' => 'Teknik Komputer Jaringan',
        'AP' => 'Administrasi Perkantoran',
        'AK' => 'Akuntansi'
    ];

    /**
     * Kriteria untuk setiap jurusan SMK
     * Berdasarkan bobot mata pelajaran yang relevan dengan jurusan
     */
    private array $majorCriteria = [
        'TKR' => [
            'mathematics' => 0.32,      // Matematika penting (sedikit lebih rendah)
            'science' => 0.38,          // IPA/Fisika sangat penting untuk mesin mobil (lebih tinggi)
            'indonesian' => 0.10,       // Bahasa Indonesia
            'english' => 0.12,          // Bahasa Inggris penting untuk manual teknik (lebih tinggi)
            'social_studies' => 0.08    // IPS (lebih rendah)
        ],
        'TSM' => [
            'mathematics' => 0.38,      // Matematika aplikatif sangat penting untuk motor (lebih tinggi)
            'science' => 0.32,          // IPA penting (sedikit lebih rendah, lebih praktis)
            'indonesian' => 0.10,       // Bahasa Indonesia
            'english' => 0.08,          // Bahasa Inggris (lebih rendah, lebih praktis)
            'social_studies' => 0.12    // IPS penting untuk interaksi customer (lebih tinggi)
        ],
        'TKJ' => [
            'mathematics' => 0.40,      // Matematika sangat penting untuk komputer
            'science' => 0.20,          // IPA untuk logika
            'indonesian' => 0.10,       // Bahasa Indonesia
            'english' => 0.20,          // Bahasa Inggris penting untuk programming
            'social_studies' => 0.10    // IPS
        ],
        'AP' => [
            'mathematics' => 0.15,      // Matematika dasar
            'science' => 0.10,          // IPA
            'indonesian' => 0.35,       // Bahasa Indonesia sangat penting untuk administrasi
            'english' => 0.30,          // Bahasa Inggris penting untuk komunikasi
            'social_studies' => 0.10    // IPS
        ],
        'AK' => [
            'mathematics' => 0.35,      // Matematika penting untuk akuntansi
            'science' => 0.10,          // IPA
            'indonesian' => 0.25,       // Bahasa Indonesia untuk komunikasi
            'english' => 0.15,          // Bahasa Inggris
            'social_studies' => 0.15    // IPS penting untuk ekonomi
        ]
    ];

    /**
     * Mapping bobot pertanyaan interest survey untuk setiap jurusan
     * Index 0-19 sesuai dengan pertanyaan 1-20
     * Total bobot per jurusan harus = 1.0
     * 
     * Bagian A (Q1-Q10): Kemampuan & Ketertarikan Akademik
     * Bagian B (Q11-Q20): Gaya Belajar & Perilaku
     */
    private array $interestWeights = [
        'TKR' => [
            // Bagian A - Kemampuan & Ketertarikan Akademik
            0.048,  // Q1: Perhitungan dan logika matematika
            0.067,  // Q2: Percobaan/praktis
            0.095,  // Q3: IPA dan teknologi modern (sangat relevan)
            0.067,  // Q4: Analisis masalah dan solusi logis
            0.124,  // Q5: Fisika/prinsip kerja alat dan mesin (SANGAT TINGGI - core TKR)
            0.010,  // Q6: Sejarah dan budaya
            0.019,  // Q7: Bekerja sama/interaksi (sedikit relevan untuk teamwork bengkel)
            0.010,  // Q8: Bisnis/administrasi/keuangan
            0.010,  // Q9: Sosial/hukum/politik
            0.019,  // Q10: Bahasa/komunikasi (sedikit relevan untuk komunikasi teknis)
            
            // Bagian B - Gaya Belajar & Perilaku
            0.086,  // Q11: Memperbaiki vs membuat baru (TINGGI - repair-oriented)
            0.019,  // Q12: Komputer/aplikasi/jaringan (tidak terlalu relevan)
            0.038,  // Q13: Angka/tabel vs kata-kata (sedang, untuk membaca manual)
            0.019,  // Q14: Membantu mengatur pekerjaan/dokumen
            0.114,  // Q15: Mempelajari bagaimana mesin/alat bekerja (SANGAT TINGGI)
            0.038,  // Q16: Rapi dan terorganisir (sedang, penting untuk kerja bengkel)
            0.048,  // Q17: Meningkatkan efisiensi/perbaikan sistem (tinggi)
            0.019,  // Q18: Berbicara/menjelaskan (komunikasi teknis)
            0.114,  // Q19: Bekerja dengan alat/mesin vs dokumen (SANGAT TINGGI)
            0.038   // Q20: Ketelitian tinggi dan tanggung jawab (penting)
        ],
        'TSM' => [
            // Bagian A - Kemampuan & Ketertarikan Akademik
            0.063,  // Q1: Perhitungan dan logika matematika (lebih tinggi, aplikatif)
            0.054,  // Q2: Percobaan/praktis (tinggi)
            0.081,  // Q3: IPA dan teknologi modern (relevan)
            0.090,  // Q4: Analisis masalah dan solusi logis (TINGGI - troubleshooting)
            0.090,  // Q5: Fisika/prinsip kerja alat dan mesin (TINGGI)
            0.009,  // Q6: Sejarah dan budaya
            0.027,  // Q7: Bekerja sama/interaksi (lebih relevan untuk customer service)
            0.018,  // Q8: Bisnis/administrasi/keuangan (sedikit relevan untuk bisnis bengkel)
            0.009,  // Q9: Sosial/hukum/politik
            0.027,  // Q10: Bahasa/komunikasi (lebih relevan untuk interaksi customer)
            
            // Bagian B - Gaya Belajar & Perilaku
            0.099,  // Q11: Memperbaiki vs membuat baru (SANGAT TINGGI - core TSM)
            0.018,  // Q12: Komputer/aplikasi/jaringan (tidak relevan)
            0.045,  // Q13: Angka/tabel vs kata-kata (sedang)
            0.018,  // Q14: Membantu mengatur pekerjaan/dokumen
            0.099,  // Q15: Mempelajari bagaimana mesin/alat bekerja (SANGAT TINGGI)
            0.027,  // Q16: Rapi dan terorganisir (sedang)
            0.063,  // Q17: Meningkatkan efisiensi/perbaikan sistem (tinggi)
            0.036,  // Q18: Berbicara/menjelaskan (komunikasi dengan customer)
            0.099,  // Q19: Bekerja dengan alat/mesin vs dokumen (SANGAT TINGGI)
            0.027   // Q20: Ketelitian tinggi dan tanggung jawab (sedang)
        ],
        'TKJ' => [
            // Bagian A - Kemampuan & Ketertarikan Akademik
            0.097,  // Q1: Perhitungan dan logika matematika (TINGGI - penting untuk programming)
            0.049,  // Q2: Percobaan/praktis (sedang, eksperimen coding)
            0.097,  // Q3: IPA dan teknologi modern (TINGGI - core TKJ)
            0.126,  // Q4: Analisis masalah dan solusi logis (SANGAT TINGGI - programming)
            0.029,  // Q5: Fisika/prinsip kerja alat dan mesin (tidak terlalu relevan)
            0.010,  // Q6: Sejarah dan budaya
            0.019,  // Q7: Bekerja sama/interaksi (sedang, kerja tim project)
            0.029,  // Q8: Bisnis/administrasi/keuangan (sedang, untuk project management)
            0.010,  // Q9: Sosial/hukum/politik
            0.029,  // Q10: Bahasa/komunikasi (sedang, dokumentasi & presentasi)
            
            // Bagian B - Gaya Belajar & Perilaku
            0.019,  // Q11: Memperbaiki vs membuat baru (lebih ke membuat)
            0.117,  // Q12: Komputer/aplikasi/jaringan (SANGAT TINGGI - core TKJ)
            0.068,  // Q13: Angka/tabel vs kata-kata (tinggi, data structure & algorithm)
            0.029,  // Q14: Membantu mengatur pekerjaan/dokumen (sedang)
            0.010,  // Q15: Mempelajari bagaimana mesin/alat bekerja (tidak relevan)
            0.049,  // Q16: Rapi dan terorganisir (sedang, code organization)
            0.107,  // Q17: Meningkatkan efisiensi/perbaikan sistem (TINGGI - optimization)
            0.039,  // Q18: Berbicara/menjelaskan (presentasi & dokumentasi)
            0.019,  // Q19: Bekerja dengan alat/mesin vs dokumen (lebih ke dokumen/kode)
            0.049   // Q20: Ketelitian tinggi dan tanggung jawab (tinggi, debugging)
        ],
        'AP' => [
            // Bagian A - Kemampuan & Ketertarikan Akademik
            0.027,  // Q1: Perhitungan dan logika matematika (dasar saja)
            0.009,  // Q2: Percobaan/praktis (tidak relevan)
            0.018,  // Q3: IPA dan teknologi modern (tidak relevan)
            0.045,  // Q4: Analisis masalah dan solusi logis (sedang)
            0.009,  // Q5: Fisika/prinsip kerja alat dan mesin (tidak relevan)
            0.063,  // Q6: Sejarah dan budaya (relevan untuk pengetahuan umum)
            0.090,  // Q7: Bekerja sama/interaksi (TINGGI - core AP)
            0.108,  // Q8: Bisnis/administrasi/keuangan (TINGGI - core AP)
            0.117,  // Q9: Sosial/hukum/politik (SANGAT TINGGI - core AP)
            0.099,  // Q10: Bahasa/komunikasi (TINGGI - core AP)
            
            // Bagian B - Gaya Belajar & Perilaku
            0.009,  // Q11: Memperbaiki vs membuat baru (tidak relevan)
            0.018,  // Q12: Komputer/aplikasi/jaringan (sedang, untuk office apps)
            0.027,  // Q13: Angka/tabel vs kata-kata (sedang, untuk laporan)
            0.090,  // Q14: Membantu mengatur pekerjaan/dokumen (TINGGI - core AP)
            0.009,  // Q15: Mempelajari bagaimana mesin/alat bekerja (tidak relevan)
            0.081,  // Q16: Rapi dan terorganisir (TINGGI - sangat penting)
            0.027,  // Q17: Meningkatkan efisiensi/perbaikan sistem (sedang)
            0.081,  // Q18: Berbicara/menjelaskan (TINGGI - komunikasi)
            0.009,  // Q19: Bekerja dengan alat/mesin vs dokumen (lebih ke dokumen)
            0.063   // Q20: Ketelitian tinggi dan tanggung jawab (tinggi)
        ],
        'AK' => [
            // Bagian A - Kemampuan & Ketertarikan Akademik
            0.094,  // Q1: Perhitungan dan logika matematika (TINGGI - core AK)
            0.019,  // Q2: Percobaan/praktis (tidak relevan)
            0.019,  // Q3: IPA dan teknologi modern (tidak relevan)
            0.075,  // Q4: Analisis masalah dan solusi logis (TINGGI - analisis keuangan)
            0.019,  // Q5: Fisika/prinsip kerja alat dan mesin (tidak relevan)
            0.019,  // Q6: Sejarah dan budaya (sedikit relevan)
            0.028,  // Q7: Bekerja sama/interaksi (sedang)
            0.142,  // Q8: Bisnis/administrasi/keuangan (SANGAT TINGGI - core AK)
            0.028,  // Q9: Sosial/hukum/politik (sedikit relevan untuk perpajakan)
            0.038,  // Q10: Bahasa/komunikasi (sedang, untuk laporan)
            
            // Bagian B - Gaya Belajar & Perilaku
            0.009,  // Q11: Memperbaiki vs membuat baru (tidak relevan)
            0.028,  // Q12: Komputer/aplikasi/jaringan (sedang, untuk software akuntansi)
            0.094,  // Q13: Angka/tabel vs kata-kata (TINGGI - core AK, laporan keuangan)
            0.066,  // Q14: Membantu mengatur pekerjaan/dokumen (tinggi, dokumen keuangan)
            0.009,  // Q15: Mempelajari bagaimana mesin/alat bekerja (tidak relevan)
            0.094,  // Q16: Rapi dan terorganisir (TINGGI - sangat penting untuk akuntansi)
            0.047,  // Q17: Meningkatkan efisiensi/perbaikan sistem (sedang, sistem akuntansi)
            0.028,  // Q18: Berbicara/menjelaskan (sedang, presentasi laporan)
            0.009,  // Q19: Bekerja dengan alat/mesin vs dokumen (lebih ke dokumen)
            0.132   // Q20: Ketelitian tinggi dan tanggung jawab (SANGAT TINGGI - core AK)
        ]
    ];

    /**
     * Mapping bobot kriteria interview untuk setiap jurusan
     * Total bobot per jurusan harus = 1.0
     */
    private array $interviewWeights = [
        'TKR' => [
            'communication_skill' => 0.14,    // Komunikasi penting untuk teamwork (sedikit lebih rendah)
            'motivation' => 0.22,             // Motivasi penting (sedikit lebih rendah)
            'personality' => 0.22,            // Kepribadian/ketelitian sangat penting untuk mesin kompleks (lebih tinggi)
            'academic_potential' => 0.28,     // Potensi akademik sangat penting untuk sistem mobil kompleks (lebih tinggi)
            'career_orientation' => 0.14      // Orientasi karir (sedikit lebih rendah)
        ],
        'TSM' => [
            'communication_skill' => 0.18,    // Komunikasi lebih penting untuk interaksi customer langsung (lebih tinggi)
            'motivation' => 0.28,             // Motivasi sangat penting untuk kerja praktis/aplikatif (lebih tinggi)
            'personality' => 0.18,            // Kepribadian baik (sedikit lebih rendah, lebih fleksibel)
            'academic_potential' => 0.20,     // Potensi akademik penting tapi lebih praktis (lebih rendah)
            'career_orientation' => 0.16      // Orientasi karir penting untuk bisnis bengkel independen (lebih tinggi)
        ],
        'TKJ' => [
            'communication_skill' => 0.20,    // Komunikasi penting untuk presentasi
            'motivation' => 0.20,             // Motivasi penting
            'personality' => 0.15,            // Kepribadian
            'academic_potential' => 0.30,     // Potensi akademik sangat penting (logika)
            'career_orientation' => 0.15      // Orientasi karir jelas
        ],
        'AP' => [
            'communication_skill' => 0.35,    // Komunikasi sangat penting untuk administrasi
            'motivation' => 0.15,             // Motivasi
            'personality' => 0.25,            // Kepribadian sangat penting (kerja dengan orang)
            'academic_potential' => 0.10,     // Potensi akademik (tidak sepenting lainnya)
            'career_orientation' => 0.15      // Orientasi karir jelas
        ],
        'AK' => [
            'communication_skill' => 0.20,    // Komunikasi penting
            'motivation' => 0.20,             // Motivasi penting
            'personality' => 0.20,            // Kepribadian (ketelitian)
            'academic_potential' => 0.25,     // Potensi akademik penting (matematika)
            'career_orientation' => 0.15      // Orientasi karir jelas
        ]
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

        // Hitung skor untuk setiap jurusan SMK beserta detail perhitungan
        $majorScores = [];
        $majorDetails = [];
        foreach (array_keys($this->majors) as $majorCode) {
            $majorScores[$majorCode] = $this->calculateMajorScore($normalizedData, $majorCode);
            
            // Simpan detail perhitungan per jurusan
            $majorDetails[$majorCode] = [
                'academic_score' => $this->calculateAcademicScore($normalizedData['academic'], $majorCode),
                'interest_score' => $this->calculateInterestScore($normalizedData['interest_answers'], $majorCode),
                'interview_score' => $this->calculateInterviewScore($normalizedData['interview_scores'], $majorCode),
                'final_score' => $majorScores[$majorCode]
            ];
        }

        // Gunakan VIKOR untuk perangkingan
        $vikorResults = $this->calculateVikor($majorScores);

        // Tambahkan detail perhitungan per jurusan ke details
        $vikorResults['details']['major_details'] = $majorDetails;

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
     * Hitung skor akademik untuk jurusan tertentu (helper method)
     */
    private function calculateAcademicScore(array $academic, string $majorCode): float
    {
        $criteria = $this->majorCriteria[$majorCode];
        $score = 0;
        foreach ($criteria as $subject => $weight) {
            $score += $academic[$subject] * $weight;
        }
        return $score;
    }

    /**
     * Normalisasi data menggunakan metode SAW
     * Mengembalikan data mentah untuk dihitung per jurusan
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

        // Ambil jawaban interest survey (array dengan 20 elemen, nilai 1-5)
        $interestAnswers = $interestSurvey->answers ?? [];
        
        // Validasi jumlah jawaban
        if (count($interestAnswers) !== 20) {
            throw new \Exception('Jumlah jawaban interest survey harus 20 pertanyaan');
        }
        
        // Normalisasi setiap jawaban (1-5 -> 0-1) untuk perhitungan per jurusan
        $normalizedInterestAnswers = array_map(function($answer) {
            // Pastikan nilai dalam range 1-5
            $answer = max(1, min(5, (int)$answer));
            return ($answer - 1) / 4; // Convert 1-5 to 0-1 scale
        }, $interestAnswers);

        // Ambil nilai interview individual (1-5 untuk setiap kriteria)
        $interviewScores = [
            'communication_skill' => $interviewScore->communication_skill / 5,    // 1-5 -> 0-1
            'motivation' => $interviewScore->motivation / 5,
            'personality' => $interviewScore->personality / 5,
            'academic_potential' => $interviewScore->academic_potential / 5,
            'career_orientation' => $interviewScore->career_orientation / 5
        ];

        return [
            'academic' => $normalizedAcademic,
            'interest_answers' => $normalizedInterestAnswers,  // Raw answers untuk dihitung per jurusan
            'interview_scores' => $interviewScores             // Raw scores untuk dihitung per jurusan
        ];
    }

    /**
     * Hitung skor untuk jurusan tertentu
     * Interest dan Interview dihitung secara spesifik per jurusan
     */
    private function calculateMajorScore(array $normalizedData, string $majorCode): float
    {
        if (!isset($this->majorCriteria[$majorCode])) {
            throw new \Exception("Jurusan {$majorCode} tidak ditemukan");
        }

        // 1. Hitung skor akademik berdasarkan kriteria jurusan
        $criteria = $this->majorCriteria[$majorCode];
        $academicScore = 0;
        foreach ($criteria as $subject => $weight) {
            $academicScore += $normalizedData['academic'][$subject] * $weight;
        }

        // 2. Hitung skor interest berdasarkan mapping per jurusan
        $interestScore = $this->calculateInterestScore($normalizedData['interest_answers'], $majorCode);

        // 3. Hitung skor interview berdasarkan mapping per jurusan
        $interviewScore = $this->calculateInterviewScore($normalizedData['interview_scores'], $majorCode);

        // 4. Hitung skor akhir dengan bobot
        $finalScore = (
            $academicScore * $this->weights['academic'] +
            $interestScore * $this->weights['interest'] +
            $interviewScore * $this->weights['interview']
        );

        return $finalScore;
    }

    /**
     * Hitung skor interest untuk jurusan tertentu
     * Berdasarkan bobot pertanyaan yang relevan dengan jurusan
     */
    private function calculateInterestScore(array $interestAnswers, string $majorCode): float
    {
        if (!isset($this->interestWeights[$majorCode])) {
            throw new \Exception("Bobot interest untuk jurusan {$majorCode} tidak ditemukan");
        }

        $weights = $this->interestWeights[$majorCode];
        $score = 0;

        // Pastikan jumlah jawaban sesuai dengan jumlah bobot (20 pertanyaan)
        $answersCount = count($interestAnswers);
        if ($answersCount !== 20) {
            throw new \Exception("Jumlah jawaban interest survey harus 20, ditemukan: {$answersCount}");
        }

        // Hitung weighted score
        for ($i = 0; $i < 20; $i++) {
            $answer = $interestAnswers[$i] ?? 0;
            $weight = $weights[$i] ?? 0;
            $score += $answer * $weight;
        }

        return $score; // Sudah dalam skala 0-1
    }

    /**
     * Hitung skor interview untuk jurusan tertentu
     * Berdasarkan bobot kriteria interview yang relevan dengan jurusan
     */
    private function calculateInterviewScore(array $interviewScores, string $majorCode): float
    {
        if (!isset($this->interviewWeights[$majorCode])) {
            throw new \Exception("Bobot interview untuk jurusan {$majorCode} tidak ditemukan");
        }

        $weights = $this->interviewWeights[$majorCode];
        $score = 0;

        // Hitung weighted score untuk setiap kriteria interview
        foreach ($weights as $criterion => $weight) {
            $criterionScore = $interviewScores[$criterion] ?? 0;
            $score += $criterionScore * $weight;
        }

        return $score; // Sudah dalam skala 0-1
    }

    /**
     * Implementasi algoritma VIKOR untuk 5 jurusan SMK
     */
    private function calculateVikor(array $majorScores): array
    {
        // Hitung nilai maksimum dan minimum
        $maxScore = max($majorScores);
        $minScore = min($majorScores);

        // Jika semua skor sama, return jurusan pertama
        if ($maxScore == $minScore) {
            $recommendedMajor = array_key_first($majorScores);
            return [
                'recommended_major' => $recommendedMajor,
                'saw_score' => $majorScores[$recommendedMajor],
                'vikor_score' => 0,
                'final_score' => $majorScores[$recommendedMajor],
                'details' => [
                    'scores' => $majorScores,
                    's_values' => array_fill_keys(array_keys($majorScores), 0),
                    'r_values' => array_fill_keys(array_keys($majorScores), 0),
                    'q_values' => array_fill_keys(array_keys($majorScores), 0),
                    'max_score' => $maxScore,
                    'min_score' => $minScore
                ]
            ];
        }

        // Hitung S (utility measure) dan R (regret measure)
        $s = [];
        $r = [];
        
        foreach ($majorScores as $major => $score) {
            // S = (max - score) / (max - min)
            $s[$major] = ($maxScore - $score) / ($maxScore - $minScore);
            
            // R = max dari semua kriteria (dalam hal ini hanya satu nilai per jurusan)
            $r[$major] = $s[$major];
        }

        // Hitung Q (compromise measure) dengan v = 0.5
        $v = 0.5;
        $q = [];
        foreach ($majorScores as $major => $score) {
            $q[$major] = $v * $s[$major] + (1 - $v) * $r[$major];
        }

        // Urutkan berdasarkan Q (terendah = terbaik)
        asort($q);
        
        // Tentukan rekomendasi berdasarkan Q terendah
        $recommendedMajor = array_key_first($q);
        $vikorScore = $q[$recommendedMajor];

        return [
            'recommended_major' => $recommendedMajor,
            'saw_score' => $majorScores[$recommendedMajor],
            'vikor_score' => $vikorScore,
            'final_score' => $majorScores[$recommendedMajor] * (1 - $vikorScore),
            'details' => [
                'scores' => $majorScores,
                's_values' => $s,
                'r_values' => $r,
                'q_values' => $q,
                'max_score' => $maxScore,
                'min_score' => $minScore,
                'major_names' => $this->majors
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

    /**
     * Get daftar jurusan SMK
     */
    public function getMajors(): array
    {
        return $this->majors;
    }

    /**
     * Get nama lengkap jurusan berdasarkan kode
     */
    public function getMajorName(string $majorCode): string
    {
        return $this->majors[$majorCode] ?? $majorCode;
    }
}
