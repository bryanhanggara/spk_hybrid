<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Sistem SPK</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 1rem 1rem 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>
                Sistem SPK - Dashboard Siswa
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user me-2"></i>
                    {{ $student->name }}
                </span>
                <a class="btn btn-outline-light btn-sm" href="{{ route('student.logout') }}">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-user-graduate me-2 text-primary"></i>
                            Selamat Datang, {{ $student->name }}!
                        </h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-id-card me-2"></i>NISN: {{ $student->nisn }} | 
                            <i class="fas fa-users me-2"></i>Kelas: {{ $student->class }} |
                            <i class="fas fa-{{ $student->gender == 'L' ? 'mars' : 'venus' }} me-2"></i>
                            {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        @if($student->interestSurvey)
                            <span class="badge bg-success status-badge">
                                <i class="fas fa-check-circle me-1"></i>Angket Sudah Diisi
                            </span>
                        @else
                            <span class="badge bg-warning status-badge">
                                <i class="fas fa-exclamation-circle me-1"></i>Angket Belum Diisi
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Angket Minat
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Isi angket minat untuk membantu sistem memberikan rekomendasi jurusan yang sesuai dengan minat dan bakat Anda.
                        </p>
                        @if($student->interestSurvey)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Angket sudah diisi. Total skor: <strong>{{ $student->interestSurvey->total_score }}</strong>
                            </div>
                            <a href="{{ route('student.questionnaire') }}" class="btn btn-primary w-100">
                                <i class="fas fa-edit me-2"></i>Edit Angket
                            </a>
                        @else
                            <a href="{{ route('student.questionnaire') }}" class="btn btn-primary w-100">
                                <i class="fas fa-pencil-alt me-2"></i>Isi Angket Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2"></i>
                            Ujian Akademik
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Kerjakan ujian untuk setiap mata pelajaran (Matematika, Bahasa Indonesia, Bahasa Inggris, IPA, IPS) untuk mendapatkan nilai akademik.
                        </p>
                        <a href="{{ route('student.test.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-play me-2"></i>Mulai Ujian Akademik
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-lightbulb me-2"></i>Petunjuk
                        </h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Isi angket dengan jujur sesuai minat dan bakat Anda
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Kerjakan ujian akademik untuk setiap mata pelajaran
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Nilai akan dihitung otomatis dan digunakan untuk perhitungan SPK
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Anda dapat mengulangi ujian kapan saja
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Status Data
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clipboard-list fa-2x {{ $student->interestSurvey ? 'text-success' : 'text-muted' }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Angket Minat</h6>
                                <small class="text-muted">
                                    {{ $student->interestSurvey ? 'Sudah diisi' : 'Belum diisi' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-book fa-2x {{ $student->academicScore ? 'text-success' : 'text-muted' }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Nilai Akademik</h6>
                                <small class="text-muted">
                                    {{ $student->academicScore ? 'Sudah diisi' : 'Belum diisi' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-comments fa-2x {{ $student->interviewScore ? 'text-success' : 'text-muted' }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Wawancara</h6>
                                <small class="text-muted">
                                    {{ $student->interviewScore ? 'Sudah diisi' : 'Belum diisi' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

