<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicScore;
use App\Models\InterestSurvey;
use App\Models\InterviewScore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Tampilkan daftar siswa
     */
    public function index()
    {
        $students = Student::with(['academicScore', 'interestSurvey', 'interviewScore'])
            ->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * Tampilkan form tambah siswa
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Simpan data siswa baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn',
            'class' => 'required|string|max:50',
            'gender' => 'required|in:L,P'
        ]);

        $student = Student::create($request->only(['name', 'nisn', 'class', 'gender']));

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    /**
     * Tampilkan detail siswa
     */
    public function show(Student $student)
    {
        $student->load(['academicScore', 'interestSurvey', 'interviewScore']);
        return view('students.show', compact('student'));
    }

    /**
     * Tampilkan form edit siswa
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update data siswa
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn,' . $student->id,
            'class' => 'required|string|max:50',
            'gender' => 'required|in:L,P',
            'major_choice_1' => 'nullable|in:TKR,TSM,TKJ,AP,AK',
            'major_choice_2' => 'nullable|in:TKR,TSM,TKJ,AP,AK'
        ]);

        $student->update($request->only(['name', 'nisn', 'class', 'gender', 'major_choice_1', 'major_choice_2']));

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Hapus data siswa
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }

    /**
     * Tampilkan form input nilai akademik
     */
    public function academicForm(Student $student)
    {
        return view('students.academic', compact('student'));
    }

    /**
     * Simpan nilai akademik
     */
    public function storeAcademic(Request $request, Student $student)
    {
        $request->validate([
            'mathematics' => 'required|numeric|min:0|max:100',
            'indonesian' => 'required|numeric|min:0|max:100',
            'english' => 'required|numeric|min:0|max:100',
            'science' => 'required|numeric|min:0|max:100',
            'social_studies' => 'required|numeric|min:0|max:100'
        ]);

        $academicScore = $student->academicScore;
        if ($academicScore) {
            $academicScore->update($request->all());
        } else {
            $student->academicScore()->create($request->all());
        }

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Nilai akademik berhasil disimpan');
    }

    /**
     * Tampilkan form angket minat
     */
    public function interestForm(Student $student)
    {
        return view('students.interest', compact('student'));
    }

    /**
     * Simpan hasil angket minat
     */
    public function storeInterest(Request $request, Student $student)
    {
        $request->validate([
            'answers' => 'required|array|size:20',
            'answers.*' => 'required|integer|min:1|max:5'
        ]);

        $answers = $request->input('answers');
        $totalScore = array_sum($answers);

        $interestSurvey = $student->interestSurvey;
        if ($interestSurvey) {
            $interestSurvey->update([
                'answers' => $answers,
                'total_score' => $totalScore
            ]);
        } else {
            $student->interestSurvey()->create([
                'answers' => $answers,
                'total_score' => $totalScore
            ]);
        }

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Hasil angket minat berhasil disimpan');
    }

    /**
     * Tampilkan form wawancara
     */
    public function interviewForm(Student $student)
    {
        return view('students.interview', compact('student'));
    }

    /**
     * Simpan hasil wawancara
     */
    public function storeInterview(Request $request, Student $student)
    {
        $request->validate([
            'communication_skill' => 'required|integer|min:1|max:5',
            'motivation' => 'required|integer|min:1|max:5',
            'personality' => 'required|integer|min:1|max:5',
            'academic_potential' => 'required|integer|min:1|max:5',
            'career_orientation' => 'required|integer|min:1|max:5'
        ]);

        $totalScore = $request->input('communication_skill') +
                     $request->input('motivation') +
                     $request->input('personality') +
                     $request->input('academic_potential') +
                     $request->input('career_orientation');

        $interviewScore = $student->interviewScore;
        if ($interviewScore) {
            $interviewScore->update(array_merge($request->all(), ['total_score' => $totalScore]));
        } else {
            $student->interviewScore()->create(array_merge($request->all(), ['total_score' => $totalScore]));
        }

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Hasil wawancara berhasil disimpan');
    }

    /**
     * Simpan pilihan jurusan siswa
     */
    public function storeMajorChoice(Request $request, Student $student)
    {
        $request->validate([
            'major_choice_1' => 'nullable|in:TKR,TSM,TKJ,AP,AK',
            'major_choice_2' => 'nullable|in:TKR,TSM,TKJ,AP,AK|different:major_choice_1'
        ], [
            'major_choice_2.different' => 'Pilihan jurusan kedua harus berbeda dengan pilihan pertama.'
        ]);

        $student->update([
            'major_choice_1' => $request->input('major_choice_1'),
            'major_choice_2' => $request->input('major_choice_2')
        ]);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Pilihan jurusan berhasil disimpan');
    }

    /**
     * Dashboard siswa (setelah login)
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        $student->load(['academicScore', 'interestSurvey', 'interviewScore', 'spkResults']);
        
        return view('student.dashboard', compact('student'));
    }

    /**
     * Tampilkan form angket untuk siswa (student-facing)
     */
    public function showQuestionnaire()
    {
        $student = Auth::guard('student')->user();
        
        return view('student.questionnaire', compact('student'));
    }

    /**
     * Simpan hasil angket dari siswa
     */
    public function submitQuestionnaire(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $request->validate([
            'answers' => 'required|array|size:20',
            'answers.*' => 'required|integer|min:1|max:5'
        ]);

        $answers = $request->input('answers');
        $totalScore = array_sum($answers);

        $interestSurvey = $student->interestSurvey;
        if ($interestSurvey) {
            $interestSurvey->update([
                'answers' => $answers,
                'total_score' => $totalScore
            ]);
            $message = 'Angket minat berhasil diperbarui';
        } else {
            $student->interestSurvey()->create([
                'answers' => $answers,
                'total_score' => $totalScore
            ]);
            $message = 'Angket minat berhasil disimpan';
        }

        return redirect()->route('student.dashboard')
            ->with('success', $message);
    }
}
