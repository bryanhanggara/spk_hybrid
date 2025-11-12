<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicQuestion;
use Illuminate\Support\Facades\DB;

class AcademicQuestionController extends Controller
{
    /**
     * Tampilkan daftar soal berdasarkan subtest
     */
    public function index(Request $request)
    {
        $subject = $request->get('subject', 'mathematics');
        
        $questions = AcademicQuestion::where('subject', $subject)
            ->ordered()
            ->paginate(20);

        $subjects = [
            'mathematics' => 'Matematika',
            'indonesian' => 'Bahasa Indonesia',
            'english' => 'Bahasa Inggris',
            'science' => 'IPA',
            'social_studies' => 'IPS'
        ];

        return view('admin.questions.index', compact('questions', 'subjects', 'subject'));
    }

    /**
     * Tampilkan form tambah soal
     */
    public function create(Request $request)
    {
        $subject = $request->get('subject', 'mathematics');
        
        $subjects = [
            'mathematics' => 'Matematika',
            'indonesian' => 'Bahasa Indonesia',
            'english' => 'Bahasa Inggris',
            'science' => 'IPA',
            'social_studies' => 'IPS'
        ];

        return view('admin.questions.create', compact('subjects', 'subject'));
    }

    /**
     * Simpan soal baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|in:mathematics,indonesian,english,science,social_studies',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'points' => 'nullable|integer|min:1|max:10',
            'order' => 'nullable|integer|min:0'
        ]);

        $question = AcademicQuestion::create([
            'subject' => $request->subject,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points ?? 1,
            'order' => $request->order ?? 0,
            'is_active' => true
        ]);

        return redirect()->route('admin.questions.index', ['subject' => $request->subject])
            ->with('success', 'Soal berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit soal
     */
    public function edit(AcademicQuestion $question)
    {
        $subjects = [
            'mathematics' => 'Matematika',
            'indonesian' => 'Bahasa Indonesia',
            'english' => 'Bahasa Inggris',
            'science' => 'IPA',
            'social_studies' => 'IPS'
        ];

        return view('admin.questions.edit', compact('question', 'subjects'));
    }

    /**
     * Update soal
     */
    public function update(Request $request, AcademicQuestion $question)
    {
        $request->validate([
            'subject' => 'required|in:mathematics,indonesian,english,science,social_studies',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'points' => 'nullable|integer|min:1|max:10',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $question->update([
            'subject' => $request->subject,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points ?? 1,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $question->is_active
        ]);

        return redirect()->route('admin.questions.index', ['subject' => $question->subject])
            ->with('success', 'Soal berhasil diperbarui');
    }

    /**
     * Hapus soal
     */
    public function destroy(AcademicQuestion $question)
    {
        $subject = $question->subject;
        $question->delete();

        return redirect()->route('admin.questions.index', ['subject' => $subject])
            ->with('success', 'Soal berhasil dihapus');
    }
}

