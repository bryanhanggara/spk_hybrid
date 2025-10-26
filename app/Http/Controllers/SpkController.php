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

        // Statistik
        $ipaCount = $results->where('recommended_major', 'IPA')->count();
        $ipsCount = $results->where('recommended_major', 'IPS')->count();

        return view('spk.results', compact('results', 'ipaCount', 'ipsCount'));
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

        return view('spk.detail', compact('result'));
    }

    /**
     * API untuk mendapatkan data grafik
     */
    public function getChartData()
    {
        $results = SpkResult::select('recommended_major', DB::raw('count(*) as count'))
            ->groupBy('recommended_major')
            ->get();

        $chartData = [
            'labels' => $results->pluck('recommended_major')->toArray(),
            'data' => $results->pluck('count')->toArray()
        ];

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
                'score' => round($result->final_score, 4)
            ];
        });

        return response()->json($rankingData);
    }
}
