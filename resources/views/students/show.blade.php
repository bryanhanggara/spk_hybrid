@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user me-2"></i>Detail Siswa</h2>
    <div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Dasar -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Nama Lengkap</th>
                        <td>{{ $student->name }}</td>
                    </tr>
                    <tr>
                        <th>NISN</th>
                        <td>{{ $student->nisn }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $student->class }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>
                            <span class="badge bg-info">
                                {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Pilihan Jurusan 1</th>
                        <td>
                            @if($student->major_choice_1)
                                <span class="badge bg-primary">{{ $student->major_choice_1 }}</span>
                            @else
                                <span class="text-muted">Belum dipilih</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Pilihan Jurusan 2</th>
                        <td>
                            @if($student->major_choice_2)
                                <span class="badge bg-secondary">{{ $student->major_choice_2 }}</span>
                            @else
                                <span class="text-muted">Belum dipilih</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Status Data -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Status Data</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-book text-primary me-2"></i>Nilai Akademik</span>
                        @if($student->academicScore)
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Input</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Belum Input</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clipboard-list text-success me-2"></i>Angket Minat</span>
                        @if($student->interestSurvey)
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Input</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Belum Input</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-comments text-info me-2"></i>Hasil Wawancara</span>
                        @if($student->interviewScore)
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Input</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Belum Input</span>
                        @endif
                    </div>
                </div>
                
                @php
                    $completeCount = 0;
                    if($student->academicScore) $completeCount++;
                    if($student->interestSurvey) $completeCount++;
                    if($student->interviewScore) $completeCount++;
                    $progress = ($completeCount / 3) * 100;
                @endphp
                
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span><strong>Kelengkapan Data</strong></span>
                        <span><strong>{{ $completeCount }}/3</strong></span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar {{ $progress == 100 ? 'bg-success' : ($progress >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                             role="progressbar" 
                             style="width: {{ $progress }}%">
                            {{ number_format($progress, 0) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Nilai -->
<div class="row">
    @if($student->academicScore)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-book text-primary me-2"></i>Nilai Akademik</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Matematika</th>
                        <td class="text-end"><strong>{{ number_format($student->academicScore->mathematics, 1) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Bahasa Indonesia</th>
                        <td class="text-end"><strong>{{ number_format($student->academicScore->indonesian, 1) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Bahasa Inggris</th>
                        <td class="text-end"><strong>{{ number_format($student->academicScore->english, 1) }}</strong></td>
                    </tr>
                    <tr>
                        <th>IPA</th>
                        <td class="text-end"><strong>{{ number_format($student->academicScore->science, 1) }}</strong></td>
                    </tr>
                    <tr>
                        <th>IPS</th>
                        <td class="text-end"><strong>{{ number_format($student->academicScore->social_studies, 1) }}</strong></td>
                    </tr>
                    <tr class="table-active">
                        <th>Rata-rata</th>
                        <td class="text-end">
                            <strong class="text-primary">
                                {{ number_format(($student->academicScore->mathematics + $student->academicScore->indonesian + $student->academicScore->english + $student->academicScore->science + $student->academicScore->social_studies) / 5, 1) }}
                            </strong>
                        </td>
                    </tr>
                </table>
                <a href="{{ route('students.academic', $student->id) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-edit me-1"></i>Edit Nilai Akademik
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-6 mb-4">
        <div class="card border-dashed">
            <div class="card-body text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Nilai Akademik Belum Diinput</h6>
                <a href="{{ route('students.academic', $student->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Input Nilai Akademik
                </a>
            </div>
        </div>
    </div>
    @endif
    
    @if($student->interestSurvey)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clipboard-list text-success me-2"></i>Angket Minat</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="text-success mb-0">{{ $student->interestSurvey->total_score }}</h3>
                    <small class="text-muted">Total Skor dari 50</small>
                </div>
                <div class="progress mt-3">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($student->interestSurvey->total_score / 50) * 100 }}%">
                        {{ number_format(($student->interestSurvey->total_score / 50) * 100, 1) }}%
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        @php
                            $answers = $student->interestSurvey->answers ?? [];
                        @endphp
                        <strong>Detail Jawaban:</strong><br>
                        @for($i = 1; $i <= 10; $i++)
                            Soal {{ $i }}: <strong>{{ $answers[$i-1] ?? '-' }}/5</strong>
                            @if($i % 5 == 0)<br>@else - @endif
                        @endfor
                    </small>
                </div>
                <a href="{{ route('students.interest', $student->id) }}" class="btn btn-sm btn-outline-success w-100 mt-3">
                    <i class="fas fa-edit me-1"></i>Edit Angket Minat
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-6 mb-4">
        <div class="card border-dashed">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Angket Minat Belum Diinput</h6>
                <a href="{{ route('students.interest', $student->id) }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>Input Angket Minat
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Hasil Wawancara -->
<div class="row">
    @if($student->interviewScore)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-comments text-info me-2"></i>Hasil Wawancara</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Komunikasi</th>
                        <td class="text-end">
                            <span class="badge bg-primary">{{ $student->interviewScore->communication_skill }}/5</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Motivasi</th>
                        <td class="text-end">
                            <span class="badge bg-success">{{ $student->interviewScore->motivation }}/5</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Kepribadian</th>
                        <td class="text-end">
                            <span class="badge bg-info">{{ $student->interviewScore->personality }}/5</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Potensi Akademik</th>
                        <td class="text-end">
                            <span class="badge bg-warning">{{ $student->interviewScore->academic_potential }}/5</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Orientasi Karir</th>
                        <td class="text-end">
                            <span class="badge bg-danger">{{ $student->interviewScore->career_orientation }}/5</span>
                        </td>
                    </tr>
                    <tr class="table-active">
                        <th>Total Skor</th>
                        <td class="text-end">
                            <strong class="text-info">{{ $student->interviewScore->total_score }}/25</strong>
                        </td>
                    </tr>
                </table>
                <a href="{{ route('students.interview', $student->id) }}" class="btn btn-sm btn-outline-info w-100">
                    <i class="fas fa-edit me-1"></i>Edit Hasil Wawancara
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-6 mb-4">
        <div class="card border-dashed">
            <div class="card-body text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Hasil Wawancara Belum Diinput</h6>
                <a href="{{ route('students.interview', $student->id) }}" class="btn btn-info">
                    <i class="fas fa-plus me-1"></i>Input Hasil Wawancara
                </a>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Tindakan SPK -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Tindakan</h5>
            </div>
            <div class="card-body">
                @if($completeCount == 3)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Data Lengkap!</strong><br>
                        Siswa ini siap untuk proses perhitungan SPK.
                    </div>
                    <a href="{{ route('spk.index') }}" class="btn btn-primary w-100">
                        <i class="fas fa-calculator me-1"></i>Hitung SPK
                    </a>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Data Belum Lengkap</strong><br>
                        Lengkapi semua data untuk melakukan perhitungan SPK.
                    </div>
                    <div class="list-group">
                        @if(!$student->academicScore)
                        <a href="{{ route('students.academic', $student->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-book text-primary me-2"></i>Input Nilai Akademik
                        </a>
                        @endif
                        @if(!$student->interestSurvey)
                        <a href="{{ route('students.interest', $student->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-clipboard-list text-success me-2"></i>Input Angket Minat
                        </a>
                        @endif
                        @if(!$student->interviewScore)
                        <a href="{{ route('students.interview', $student->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-comments text-info me-2"></i>Input Hasil Wawancara
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Pilihan Jurusan Siswa -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Pilihan Jurusan Siswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.major-choice.store', $student->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="major_choice_1" class="form-label">
                                <i class="fas fa-star me-2"></i>Pilihan Jurusan 1 (Prioritas Utama)
                            </label>
                            <select class="form-select @error('major_choice_1') is-invalid @enderror" 
                                    id="major_choice_1" 
                                    name="major_choice_1">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="TKR" {{ old('major_choice_1', $student->major_choice_1) == 'TKR' ? 'selected' : '' }}>TKR - Teknik Kendaraan Ringan</option>
                                <option value="TSM" {{ old('major_choice_1', $student->major_choice_1) == 'TSM' ? 'selected' : '' }}>TSM - Teknik Sepeda Motor</option>
                                <option value="TKJ" {{ old('major_choice_1', $student->major_choice_1) == 'TKJ' ? 'selected' : '' }}>TKJ - Teknik Komputer Jaringan</option>
                                <option value="AP" {{ old('major_choice_1', $student->major_choice_1) == 'AP' ? 'selected' : '' }}>AP - Administrasi Perkantoran</option>
                                <option value="AK" {{ old('major_choice_1', $student->major_choice_1) == 'AK' ? 'selected' : '' }}>AK - Akuntansi</option>
                            </select>
                            @error('major_choice_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="major_choice_2" class="form-label">
                                <i class="fas fa-star-half-alt me-2"></i>Pilihan Jurusan 2 (Alternatif)
                            </label>
                            <select class="form-select @error('major_choice_2') is-invalid @enderror" 
                                    id="major_choice_2" 
                                    name="major_choice_2">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="TKR" {{ old('major_choice_2', $student->major_choice_2) == 'TKR' ? 'selected' : '' }}>TKR - Teknik Kendaraan Ringan</option>
                                <option value="TSM" {{ old('major_choice_2', $student->major_choice_2) == 'TSM' ? 'selected' : '' }}>TSM - Teknik Sepeda Motor</option>
                                <option value="TKJ" {{ old('major_choice_2', $student->major_choice_2) == 'TKJ' ? 'selected' : '' }}>TKJ - Teknik Komputer Jaringan</option>
                                <option value="AP" {{ old('major_choice_2', $student->major_choice_2) == 'AP' ? 'selected' : '' }}>AP - Administrasi Perkantoran</option>
                                <option value="AK" {{ old('major_choice_2', $student->major_choice_2) == 'AK' ? 'selected' : '' }}>AK - Akuntansi</option>
                            </select>
                            @error('major_choice_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Pilihan jurusan ini akan digunakan untuk membandingkan dengan hasil rekomendasi sistem setelah perhitungan SPK dilakukan.
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Pilihan Jurusan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const majorChoice1 = document.getElementById('major_choice_1');
    const majorChoice2 = document.getElementById('major_choice_2');
    
    // Prevent selecting the same major for both choices
    function validateChoices() {
        if (majorChoice1.value && majorChoice2.value && majorChoice1.value === majorChoice2.value) {
            majorChoice2.setCustomValidity('Pilihan jurusan kedua harus berbeda dengan pilihan pertama.');
            majorChoice2.classList.add('is-invalid');
        } else {
            majorChoice2.setCustomValidity('');
            majorChoice2.classList.remove('is-invalid');
        }
    }
    
    majorChoice1.addEventListener('change', validateChoices);
    majorChoice2.addEventListener('change', validateChoices);
    
    // Initial validation
    validateChoices();
});
</script>

<style>
    .border-dashed {
        border: 2px dashed #dee2e6;
    }
    .table th {
        font-weight: 600;
    }
</style>
@endsection

