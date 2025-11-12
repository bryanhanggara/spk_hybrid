<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian {{ $subjectNames[$subject] }} - Sistem SPK</title>
    
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
        }
        .correct-answer {
            background-color: #d4edda;
            border-color: #28a745;
        }
        .wrong-answer {
            background-color: #f8d7da;
            border-color: #dc3545;
        }
        .not-answered {
            background-color: #fff3cd;
            border-color: #ffc107;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('student.test.index') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Sistem SPK
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user me-2"></i>
                    {{ $student->name }}
                </span>
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

        <!-- Score Summary -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Hasil Ujian {{ $subjectNames[$subject] }}
                </h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Nilai</h6>
                        <h2 class="mb-0 {{ $score >= 80 ? 'text-success' : ($score >= 60 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format($score, 2) }}
                        </h2>
                        <small class="text-muted">dari 100</small>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Benar</h6>
                        <h2 class="mb-0 text-success">
                            {{ $answers->where('is_correct', true)->count() }}
                        </h2>
                        <small class="text-muted">dari {{ $questions->count() }} soal</small>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Salah</h6>
                        <h2 class="mb-0 text-danger">
                            {{ $answers->where('is_correct', false)->count() }}
                        </h2>
                        <small class="text-muted">dari {{ $questions->count() }} soal</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Jawaban -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Detail Jawaban
                </h5>
            </div>
            <div class="card-body">
                @foreach($questions as $index => $question)
                    @php
                        $answer = $answers->get($question->id);
                        $isCorrect = $answer && $answer->is_correct;
                        $userAnswer = $answer ? $answer->answer : null;
                    @endphp
                    
                    <div class="card mb-3 {{ $isCorrect ? 'border-success' : ($userAnswer ? 'border-danger' : 'border-warning') }}">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <span class="badge {{ $isCorrect ? 'bg-success' : ($userAnswer ? 'bg-danger' : 'bg-warning') }} me-2" 
                                      style="font-size: 1rem; padding: 0.5rem 0.75rem;">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2">{{ $question->question }}</h6>
                                    
                                    <div class="ms-3">
                                        @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $option)
                                            @php
                                                $isUserAnswer = $userAnswer === $key;
                                                $isCorrectAnswer = $question->correct_answer === $key;
                                                $class = '';
                                                if ($isCorrectAnswer) {
                                                    $class = 'correct-answer';
                                                } elseif ($isUserAnswer && !$isCorrectAnswer) {
                                                    $class = 'wrong-answer';
                                                }
                                            @endphp
                                            <div class="p-2 mb-2 border rounded {{ $class }}">
                                                <span class="badge bg-{{ $key === 'a' ? 'danger' : ($key === 'b' ? 'warning' : ($key === 'c' ? 'info' : 'success')) }} me-2">
                                                    {{ strtoupper($key) }}
                                                </span>
                                                {{ $option }}
                                                @if($isCorrectAnswer)
                                                    <span class="badge bg-success ms-2">
                                                        <i class="fas fa-check me-1"></i>Jawaban Benar
                                                    </span>
                                                @endif
                                                @if($isUserAnswer && !$isCorrectAnswer)
                                                    <span class="badge bg-danger ms-2">
                                                        <i class="fas fa-times me-1"></i>Jawaban Anda
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if(!$userAnswer)
                                        <div class="alert alert-warning mt-2 mb-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Anda tidak menjawab soal ini
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('student.test.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Ujian
            </a>
           
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

