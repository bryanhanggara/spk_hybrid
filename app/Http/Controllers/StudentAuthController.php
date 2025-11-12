<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentAuthController extends Controller
{
    /**
     * Tampilkan halaman login siswa
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }
        
        return view('student.auth.login');
    }
    
    /**
     * Redirect ke halaman login jika belum terautentikasi
     */
    public function redirectToLogin()
    {
        return redirect()->route('student.login');
    }

    /**
     * Proses login siswa
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'nisn' => 'required|string'
        ], [
            'name.required' => 'Nama harus diisi',
            'nisn.required' => 'NISN harus diisi'
        ]);

        // Cari siswa berdasarkan nama dan NISN
        $student = Student::where('name', $request->name)
            ->where('nisn', $request->nisn)
            ->first();

        if ($student) {
            // Login siswa menggunakan session
            Auth::guard('student')->login($student);
            Session::put('student_id', $student->id);
            
            return redirect()->route('student.dashboard')
                ->with('success', 'Selamat datang, ' . $student->name . '!');
        }

        return back()->withErrors([
            'login_error' => 'Nama atau NISN tidak ditemukan. Silakan coba lagi.'
        ])->withInput($request->only('name'));
    }

    /**
     * Logout siswa
     */
    public function logout()
    {
        Auth::guard('student')->logout();
        Session::forget('student_id');
        
        return redirect()->route('student.login')
            ->with('success', 'Anda telah logout');
    }
}

