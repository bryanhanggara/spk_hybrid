@extends('layouts.app')

@section('title', 'Dashboard SPK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard SPK</h2>
    <div>
        <a href="{{ route('students.create') }}" class="btn btn-primary me-2">
            <i class="fas fa-plus me-1"></i>Tambah Siswa
        </a>
        <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-users me-1"></i>Kelola Siswa
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h3 class="text-primary">{{ $totalStudents }}</h3>
                <p class="text-muted mb-0">Total Siswa</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-calculator fa-3x text-success mb-3"></i>
                <h3 class="text-success">{{ $completedCalculations }}</h3>
                <p class="text-muted mb-0">Perhitungan Selesai</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-percentage fa-3x text-info mb-3"></i>
                <h3 class="text-info">{{ $totalStudents > 0 ? round(($completedCalculations / $totalStudents) * 100, 1) : 0 }}%</h3>
                <p class="text-muted mb-0">Tingkat Kelengkapan</p>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Aksi SPK</h5>
            </div>
            <div class="card-body">
                @if($totalStudents > 0)
                    <form action="{{ route('spk.calculate') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg me-3" 
                                onclick="return confirm('Apakah Anda yakin ingin menghitung rekomendasi untuk semua siswa?')">
                            <i class="fas fa-calculator me-2"></i>Hitung Rekomendasi SPK
                        </button>
                    </form>
                    
                    @if($completedCalculations > 0)
                        <a href="{{ route('spk.results') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-chart-bar me-2"></i>Lihat Hasil SPK
                        </a>
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada data siswa. Silakan tambah data siswa terlebih dahulu.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Students -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Data Siswa Terbaru</h5>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status Data</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students->take(10) as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->nisn }}</td>
                                    <td>{{ $student->class }}</td>
                                    <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>
                                        @php
                                            $status = [];
                                            if($student->academicScore) $status[] = 'Akademik';
                                            if($student->interestSurvey) $status[] = 'Angket';
                                            if($student->interviewScore) $status[] = 'Wawancara';
                                        @endphp
                                        
                                        @if(count($status) == 3)
                                            <span class="badge bg-success">Lengkap</span>
                                        @elseif(count($status) > 0)
                                            <span class="badge bg-warning">{{ count($status) }}/3</span>
                                        @else
                                            <span class="badge bg-danger">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($students->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                                Lihat Semua Data Siswa
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada data siswa</h5>
                        <p class="text-muted">Silakan tambah data siswa terlebih dahulu</p>
                        <a href="{{ route('students.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Tambah Siswa Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

