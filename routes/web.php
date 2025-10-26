<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\StudentController;

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

// Routes untuk Student Management
Route::resource('students', StudentController::class);
Route::prefix('students')->name('students.')->group(function () {
    Route::get('/{student}/academic', [StudentController::class, 'academicForm'])->name('academic');
    Route::post('/{student}/academic', [StudentController::class, 'storeAcademic'])->name('academic.store');
    Route::get('/{student}/interest', [StudentController::class, 'interestForm'])->name('interest');
    Route::post('/{student}/interest', [StudentController::class, 'storeInterest'])->name('interest.store');
    Route::get('/{student}/interview', [StudentController::class, 'interviewForm'])->name('interview');
    Route::post('/{student}/interview', [StudentController::class, 'storeInterview'])->name('interview.store');
});
