@extends('layouts.app')

@section('title', 'Detail Perhitungan SPK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-calculator me-2"></i>Detail Perhitungan SPK</h2>
    <div>
        <a href="{{ route('spk.results') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Hasil
        </a>
    </div>
</div>

<!-- Student Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Siswa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $result->student->name }}</p>
                        <p><strong>NISN:</strong> {{ $result->student->nisn }}</p>
                        <p><strong>Kelas:</strong> {{ $result->student->class }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Jenis Kelamin:</strong> {{ $result->student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        <p><strong>Peringkat:</strong> <span class="badge bg-primary fs-6">#{{ $result->rank }}</span></p>
                        <p><strong>Rekomendasi:</strong> 
                            @php
                                $badgeClass = 'bg-secondary';
                                $details = $result->calculation_details ?? [];
                                $majorNames = $details['major_names'] ?? $majors ?? [];
                                if($result->recommended_major == 'TKR') $badgeClass = 'bg-primary';
                                elseif($result->recommended_major == 'TSM') $badgeClass = 'bg-success';
                                elseif($result->recommended_major == 'TKJ') $badgeClass = 'bg-info';
                                elseif($result->recommended_major == 'AP') $badgeClass = 'bg-warning';
                                elseif($result->recommended_major == 'AK') $badgeClass = 'bg-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6">
                                {{ $result->recommended_major }}
                            </span>
                            @if(isset($majorNames[$result->recommended_major]))
                                <br><small class="text-muted">{{ $majorNames[$result->recommended_major] }}</small>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comparison: Student Choice vs System Recommendation -->
@php
    $student = $result->student;
    $recommendedMajor = $result->recommended_major;
    $studentChoice1 = $student->major_choice_1;
    $studentChoice2 = $student->major_choice_2;
    $matches = false;
    $matchLevel = 0; // 0 = no match, 1 = matches choice 2, 2 = matches choice 1
    
    if ($studentChoice1 && $recommendedMajor == $studentChoice1) {
        $matches = true;
        $matchLevel = 2; // Perfect match with first choice
    } elseif ($studentChoice2 && $recommendedMajor == $studentChoice2) {
        $matches = true;
        $matchLevel = 1; // Match with second choice
    }
@endphp

@if($studentChoice1 || $studentChoice2)
<div class="row mb-4">
    <div class="col-12">
        <div class="card {{ $matches ? 'border-success' : 'border-warning' }}">
            <div class="card-header {{ $matches ? 'bg-success text-white' : 'bg-warning' }}">
                <h5 class="mb-0">
                    <i class="fas fa-balance-scale me-2"></i>Perbandingan: Pilihan Kamu vs Rekomendasi Sistem
                </h5>
            </div>
            <div class="card-body">
                @if($matches)
                    <!-- Match Scenario -->
                    <div class="alert alert-success mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-check-circle me-2"></i>Selamat! Rekomendasi Sistem Sesuai dengan Pilihan Kamu
                        </h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Pilihan Kamu:</strong></p>
                                <ul class="list-unstyled">
                                    @if($studentChoice1)
                                        <li>
                                            <span class="badge bg-primary fs-6">1. {{ $studentChoice1 }}</span>
                                            @if(isset($majors[$studentChoice1]))
                                                <br><small class="text-muted">{{ $majors[$studentChoice1] }}</small>
                                            @endif
                                        </li>
                                    @endif
                                    @if($studentChoice2)
                                        <li class="mt-2">
                                            <span class="badge bg-secondary fs-6">2. {{ $studentChoice2 }}</span>
                                            @if(isset($majors[$studentChoice2]))
                                                <br><small class="text-muted">{{ $majors[$studentChoice2] }}</small>
                                            @endif
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Hasil Rekomendasi Sistem:</strong></p>
                                @php
                                    $badgeClass = 'bg-secondary';
                                    if($recommendedMajor == 'TKR') $badgeClass = 'bg-primary';
                                    elseif($recommendedMajor == 'TSM') $badgeClass = 'bg-success';
                                    elseif($recommendedMajor == 'TKJ') $badgeClass = 'bg-info';
                                    elseif($recommendedMajor == 'AP') $badgeClass = 'bg-warning';
                                    elseif($recommendedMajor == 'AK') $badgeClass = 'bg-danger';
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6">
                                    ✅ {{ $recommendedMajor }}
                                </span>
                                @if(isset($majors[$recommendedMajor]))
                                    <br><small class="text-muted">{{ $majors[$recommendedMajor] }}</small>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <p class="mb-0">
                            <strong>Kesimpulan:</strong> 
                            @if($matchLevel == 2)
                                Sistem merekomendasikan <strong>{{ $recommendedMajor }}</strong> yang cocok dengan pilihan utama kamu! 
                                Berdasarkan hasil angket dan wawancara, kamu paling cocok di jurusan {{ $recommendedMajor }}.
                            @else
                                Sistem merekomendasikan <strong>{{ $recommendedMajor }}</strong> yang cocok dengan pilihan alternatif kamu. 
                                Ini menunjukkan bahwa analisis sistem mendukung pilihan kamu.
                            @endif
                        </p>
                    </div>
                @else
                    <!-- No Match Scenario -->
                    <div class="alert alert-warning mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Rekomendasi Sistem Berbeda dengan Pilihan Kamu
                        </h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Pilihan Kamu:</strong></p>
                                <ul class="list-unstyled">
                                    @if($studentChoice1)
                                        <li>
                                            <span class="badge bg-primary fs-6">1. {{ $studentChoice1 }}</span>
                                            @if(isset($majors[$studentChoice1]))
                                                <br><small class="text-muted">{{ $majors[$studentChoice1] }}</small>
                                            @endif
                                        </li>
                                    @endif
                                    @if($studentChoice2)
                                        <li class="mt-2">
                                            <span class="badge bg-secondary fs-6">2. {{ $studentChoice2 }}</span>
                                            @if(isset($majors[$studentChoice2]))
                                                <br><small class="text-muted">{{ $majors[$studentChoice2] }}</small>
                                            @endif
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Hasil Rekomendasi Sistem:</strong></p>
                                @php
                                    $badgeClass = 'bg-secondary';
                                    if($recommendedMajor == 'TKR') $badgeClass = 'bg-primary';
                                    elseif($recommendedMajor == 'TSM') $badgeClass = 'bg-success';
                                    elseif($recommendedMajor == 'TKJ') $badgeClass = 'bg-info';
                                    elseif($recommendedMajor == 'AP') $badgeClass = 'bg-warning';
                                    elseif($recommendedMajor == 'AK') $badgeClass = 'bg-danger';
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6">
                                    {{ $recommendedMajor }}
                                </span>
                                @if(isset($majors[$recommendedMajor]))
                                    <br><small class="text-muted">{{ $majors[$recommendedMajor] }}</small>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <p class="mb-0">
                            <strong>Kesimpulan:</strong> 
                            Tapi hasil analisis menunjukkan kamu lebih cocok di <strong>{{ $recommendedMajor }}</strong> karena minat dan wawancara kamu tinggi di bidang ini. 
                            Sistem menganalisis berdasarkan nilai akademik, hasil angket minat, dan hasil wawancara untuk memberikan rekomendasi yang paling sesuai dengan kemampuan dan minat kamu.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Scores -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                <h4 class="text-primary">{{ number_format($result->saw_score, 4) }}</h4>
                <p class="text-muted mb-0">Skor SAW</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                <h4 class="text-success">{{ number_format($result->vikor_score, 4) }}</h4>
                <p class="text-muted mb-0">Skor VIKOR</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                <h4 class="text-warning">{{ number_format($result->final_score, 4) }}</h4>
                <p class="text-muted mb-0">Skor Akhir</p>
            </div>
        </div>
    </div>
</div>

<!-- Calculation Details -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Detail Perhitungan</h5>
            </div>
            <div class="card-body">
                @php
                    $details = $result->calculation_details;
                @endphp
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-chart-pie me-2"></i>Skor untuk Setiap Jurusan</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jurusan</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $majorNames = $details['major_names'] ?? [];
                                    $sortedScores = $details['scores'];
                                    arsort($sortedScores); // Sort by score descending
                                @endphp
                                @foreach($sortedScores as $major => $score)
                                <tr class="{{ $major == $result->recommended_major ? 'table-success' : '' }}">
                                    <td>
                                        <strong>{{ $major }}</strong>
                                        @if(isset($majorNames[$major]))
                                            <br><small class="text-muted">{{ $majorNames[$major] }}</small>
                                        @endif
                                    </td>
                                    <td><strong>{{ number_format($score, 4) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6><i class="fas fa-calculator me-2"></i>Nilai VIKOR</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jurusan</th>
                                        <th>S (Utility)</th>
                                        <th>R (Regret)</th>
                                        <th>Q (Compromise)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $majorNames = $details['major_names'] ?? [];
                                        $sortedScores = $details['scores'];
                                        arsort($sortedScores); // Sort by score descending
                                    @endphp
                                    @foreach($sortedScores as $major => $score)
                                    <tr class="{{ $major == $result->recommended_major ? 'table-success' : '' }}">
                                        <td>
                                            <strong>{{ $major }}</strong>
                                            @if(isset($majorNames[$major]))
                                                <br><small class="text-muted">{{ Str::limit($majorNames[$major], 25) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($details['s_values'][$major] ?? 0, 4) }}</td>
                                        <td>{{ number_format($details['r_values'][$major] ?? 0, 4) }}</td>
                                        <td><strong>{{ number_format($details['q_values'][$major] ?? 0, 4) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Jurusan dengan Q terendah adalah rekomendasi terbaik.
                        </small>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <h6><i class="fas fa-info-circle me-2"></i>Penjelasan Metode</h6>
                        <div class="alert alert-info">
                            <h6>Metode SAW (Simple Additive Weighting):</h6>
                            <ul class="mb-0">
                                <li>Normalisasi data ke skala 0-1</li>
                                <li>Pembobotan berdasarkan kriteria jurusan</li>
                                <li>Bobot: Akademik (40%), Minat (30%), Wawancara (30%)</li>
                            </ul>
                            
                            <h6 class="mt-3">Metode VIKOR (VlseKriterijumska Optimizacija I Kompromisno Resenje):</h6>
                            <ul class="mb-0">
                                <li>S = Utility measure (ukuran utilitas)</li>
                                <li>R = Regret measure (ukuran penyesalan)</li>
                                <li>Q = Compromise measure (ukuran kompromi)</li>
                                <li>Rekomendasi berdasarkan Q terendah</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
