<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Akademik - Sistem SPK</title>
    
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
        .subject-card {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('student.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Sistem SPK
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user me-2"></i>
                    {{ $student->name }}
                </span>
                <a class="btn btn-outline-light btn-sm" href="{{ route('student.dashboard') }}">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Ujian Akademik
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Pilih subtest yang ingin Anda kerjakan. Setiap subtest memiliki soal yang berbeda dan nilai akan dihitung secara terpisah.
                </p>
            </div>
        </div>

        <div class="row">
            @foreach($subjects as $subjectKey => $subjectData)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card subject-card h-100 border-{{ $subjectData['color'] }}">
                    <div class="card-header bg-{{ $subjectData['color'] }} text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-{{ $subjectData['icon'] }} me-2"></i>
                            {{ $subjectData['name'] }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-question-circle me-1"></i>
                                Jumlah Soal: <strong>{{ $subjectData['questions_count'] }}</strong>
                            </small>
                        </div>
                        
                        @if($subjectData['questions_count'] > 0)
                            @if($subjectData['completed'])
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Sudah Selesai</strong>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('student.test.result', $subjectKey) }}" class="btn btn-outline-{{ $subjectData['color'] }}">
                                        <i class="fas fa-eye me-1"></i>Lihat Hasil
                                    </a>
                                    
                                </div>
                            @else
                                <div class="d-grid">
                                    <a href="{{ route('student.test.show', $subjectKey) }}" class="btn btn-{{ $subjectData['color'] }}">
                                        <i class="fas fa-play me-1"></i>Mulai Ujian
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Belum ada soal tersedia
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Setiap subtest memiliki soal yang berbeda
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Nilai akan dihitung secara otomatis setelah Anda menyelesaikan ujian
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Anda dapat mengulangi ujian kapan saja
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Nilai akan tersimpan ke sistem dan digunakan untuk perhitungan SPK
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

