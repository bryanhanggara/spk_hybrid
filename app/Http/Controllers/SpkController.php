<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SpkResult;
use App\Services\SawVikorService;
use Illuminate\Support\Facades\DB;

class SpkController extends Controller
{
    protected $sawVikorService;

    public function __construct(SawVikorService $sawVikorService)
    {
        $this->sawVikorService = $sawVikorService;
    }

    /**
     * Tampilkan halaman dashboard SPK
     */
    public function index()
    {
        $students = Student::with(['academicScore', 'interestSurvey', 'interviewScore'])
            ->whereHas('academicScore')
            ->whereHas('interestSurvey')
            ->whereHas('interviewScore')
            ->get();

        $totalStudents = $students->count();
        $completedCalculations = SpkResult::count();

        return view('spk.index', compact('students', 'totalStudents', 'completedCalculations'));
    }

    /**
     * Hitung rekomendasi untuk semua siswa
     */
    public function calculateAll()
    {
        try {
            DB::beginTransaction();

            // Hapus hasil sebelumnya
            SpkResult::truncate();

            // Hitung peringkat untuk semua siswa
            $results = $this->sawVikorService->calculateRankings();

            // Simpan hasil ke database
            foreach ($results as $result) {
                SpkResult::create($result);
            }

            DB::commit();

            return redirect()->route('spk.results')
                ->with('success', 'Perhitungan SPK berhasil dilakukan untuk ' . count($results) . ' siswa');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan hasil perhitungan SPK
     */
    public function results()
    {
        $results = SpkResult::with('student')
            ->orderBy('rank')
            ->get();

        // Statistik untuk semua jurusan SMK
        $majorStats = [];
        $majors = $this->sawVikorService->getMajors();
        foreach ($majors as $code => $name) {
            $majorStats[$code] = [
                'code' => $code,
                'name' => $name,
                'count' => $results->where('recommended_major', $code)->count()
            ];
        }

        return view('spk.results', compact('results', 'majorStats'));
    }

    /**
     * Tampilkan detail perhitungan untuk siswa tertentu
     */
    public function showDetail($studentId)
    {
        $result = SpkResult::with('student')
            ->where('student_id', $studentId)
            ->first();

        if (!$result) {
            return redirect()->route('spk.index')
                ->with('error', 'Data tidak ditemukan');
        }

        $majors = $this->sawVikorService->getMajors();
        return view('spk.detail', compact('result', 'majors'));
    }

    /**
     * API untuk mendapatkan data grafik
     */
    public function getChartData()
    {
        $results = SpkResult::select('recommended_major', DB::raw('count(*) as count'))
            ->groupBy('recommended_major')
            ->get();

        $majors = $this->sawVikorService->getMajors();
        
        // Pastikan semua jurusan ada di chart, bahkan jika count = 0
        $chartData = [
            'labels' => [],
            'data' => [],
            'full_names' => []
        ];
        
        foreach ($majors as $code => $name) {
            $result = $results->firstWhere('recommended_major', $code);
            $chartData['labels'][] = $code;
            $chartData['full_names'][] = $name;
            $chartData['data'][] = $result ? $result->count : 0;
        }

        return response()->json($chartData);
    }

    /**
     * API untuk mendapatkan data ranking
     */
    public function getRankingData()
    {
        $results = SpkResult::with('student')
            ->orderBy('rank')
            ->limit(10)
            ->get();

        $rankingData = $results->map(function ($result) {
            return [
                'rank' => $result->rank,
                'name' => $result->student->name,
                'major' => $result->recommended_major,
                'major_name' => $this->sawVikorService->getMajorName($result->recommended_major),
                'score' => round($result->final_score, 4)
            ];
        });

        return response()->json($rankingData);
    }
}
