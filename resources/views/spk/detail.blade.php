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
                            <span class="badge {{ $result->recommended_major == 'IPA' ? 'badge-ipa' : 'badge-ips' }} fs-6">
                                {{ $result->recommended_major }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                                @foreach($details['scores'] as $major => $score)
                                <tr class="{{ $major == $result->recommended_major ? 'table-success' : '' }}">
                                    <td>{{ $major }}</td>
                                    <td>{{ number_format($score, 4) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6><i class="fas fa-calculator me-2"></i>Nilai VIKOR</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>IPA</th>
                                    <th>IPS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>S (Utility)</td>
                                    <td>{{ number_format($details['s_values']['IPA'], 4) }}</td>
                                    <td>{{ number_format($details['s_values']['IPS'], 4) }}</td>
                                </tr>
                                <tr>
                                    <td>R (Regret)</td>
                                    <td>{{ number_format($details['r_values']['IPA'], 4) }}</td>
                                    <td>{{ number_format($details['r_values']['IPS'], 4) }}</td>
                                </tr>
                                <tr>
                                    <td>Q (Compromise)</td>
                                    <td>{{ number_format($details['q_values']['IPA'], 4) }}</td>
                                    <td>{{ number_format($details['q_values']['IPS'], 4) }}</td>
                                </tr>
                            </tbody>
                        </table>
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
