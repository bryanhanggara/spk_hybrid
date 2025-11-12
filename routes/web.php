<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\AcademicQuestionController;
use App\Http\Controllers\StudentTestController;

Route::get('/', function () {
    return redirect()->route('spk.index');
});

// Routes untuk SPK
Route::prefix('spk')->name('spk.')->group(function () {
    Route::get('/', [SpkController::class, 'index'])->name('index');
    Route::post('/calculate', [SpkController::class, 'calculateAll'])->name('calculate');
    Route::get('/results', [SpkController::class, 'results'])->name('results');
    Route::get('/detail/{studentId}', [SpkController::class, 'showDetail'])->name('detail');
    Route::get('/chart-data', [SpkController::class, 'getChartData'])->name('chart.data');
    Route::get('/ranking-data', [SpkController::class, 'getRankingData'])->name('ranking.data');
});

// Routes untuk Student Management (Admin)
Route::resource('students', StudentController::class);
Route::prefix('students')->name('students.')->group(function () {
    Route::get('/{student}/academic', [StudentController::class, 'academicForm'])->name('academic');
    Route::post('/{student}/academic', [StudentController::class, 'storeAcademic'])->name('academic.store');
    Route::get('/{student}/interest', [StudentController::class, 'interestForm'])->name('interest');
    Route::post('/{student}/interest', [StudentController::class, 'storeInterest'])->name('interest.store');
    Route::get('/{student}/interview', [StudentController::class, 'interviewForm'])->name('interview');
    Route::post('/{student}/interview', [StudentController::class, 'storeInterview'])->name('interview.store');
    Route::post('/{student}/major-choice', [StudentController::class, 'storeMajorChoice'])->name('major-choice.store');
});

// Routes untuk Student Authentication
Route::prefix('student')->name('student.')->group(function () {
    // Login routes (public)
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login');
    
    // Protected routes (require authentication)
    Route::middleware(['auth:student'])->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/questionnaire', [StudentController::class, 'showQuestionnaire'])->name('questionnaire');
        Route::post('/questionnaire', [StudentController::class, 'submitQuestionnaire'])->name('questionnaire.submit');
        
        // Routes untuk ujian akademik
        Route::prefix('test')->name('test.')->group(function () {
            Route::get('/', [StudentTestController::class, 'index'])->name('index');
            Route::get('/{subject}', [StudentTestController::class, 'showTest'])->name('show');
            Route::post('/{subject}', [StudentTestController::class, 'submitTest'])->name('submit');
            Route::get('/{subject}/result', [StudentTestController::class, 'showResult'])->name('result');
        });
        
        Route::get('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    });
});

// Routes untuk Admin - Kelola Soal
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('questions', AcademicQuestionController::class);
    Route::get('/questions', [AcademicQuestionController::class, 'index'])->name('questions.index');
});
