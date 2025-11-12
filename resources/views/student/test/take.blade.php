<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian {{ $subjectNames[$subject] }} - Sistem SPK</title>
    
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
        .question-card {
            margin-bottom: 1.5rem;
        }
        .option-label {
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-label:hover {
            background-color: #f8f9fa;
        }
        .option-input:checked + .option-label {
            background-color: #e7f3ff;
            border-color: #0d6efd;
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
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Ujian {{ $subjectNames[$subject] }}
                </h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Jawab semua pertanyaan dengan memilih salah satu opsi (A, B, C, atau D). Pastikan Anda menjawab semua pertanyaan sebelum mengirim.
                </p>
            </div>
        </div>

        <form action="{{ route('student.test.submit', $subject) }}" method="POST" id="testForm">
            @csrf
            
            @foreach($questions as $index => $question)
            <div class="card question-card">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <span class="badge bg-primary me-2" style="font-size: 1rem; padding: 0.5rem 0.75rem;">
                            {{ $index + 1 }}
                        </span>
                        <h5 class="mb-0 flex-grow-1">{{ $question->question }}</h5>
                    </div>
                    
                    <div class="ms-4">
                        <div class="mb-2">
                            <input type="radio" 
                                   class="option-input d-none" 
                                   name="answers[{{ $question->id }}]" 
                                   id="q{{ $question->id }}_a" 
                                   value="a"
                                   {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] === 'a' ? 'checked' : '' }}>
                            <label for="q{{ $question->id }}_a" class="option-label d-block p-3 border rounded">
                                <span class="badge bg-danger me-2">A</span>
                                {{ $question->option_a }}
                            </label>
                        </div>
                        
                        <div class="mb-2">
                            <input type="radio" 
                                   class="option-input d-none" 
                                   name="answers[{{ $question->id }}]" 
                                   id="q{{ $question->id }}_b" 
                                   value="b"
                                   {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] === 'b' ? 'checked' : '' }}>
                            <label for="q{{ $question->id }}_b" class="option-label d-block p-3 border rounded">
                                <span class="badge bg-warning me-2">B</span>
                                {{ $question->option_b }}
                            </label>
                        </div>
                        
                        <div class="mb-2">
                            <input type="radio" 
                                   class="option-input d-none" 
                                   name="answers[{{ $question->id }}]" 
                                   id="q{{ $question->id }}_c" 
                                   value="c"
                                   {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] === 'c' ? 'checked' : '' }}>
                            <label for="q{{ $question->id }}_c" class="option-label d-block p-3 border rounded">
                                <span class="badge bg-info me-2">C</span>
                                {{ $question->option_c }}
                            </label>
                        </div>
                        
                        <div class="mb-0">
                            <input type="radio" 
                                   class="option-input d-none" 
                                   name="answers[{{ $question->id }}]" 
                                   id="q{{ $question->id }}_d" 
                                   value="d"
                                   {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] === 'd' ? 'checked' : '' }}>
                            <label for="q{{ $question->id }}_d" class="option-label d-block p-3 border rounded">
                                <span class="badge bg-success me-2">D</span>
                                {{ $question->option_d }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="card bg-light">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-2">Progress</h6>
                            <h4 class="mb-0">
                                <span id="answeredCount">0</span> / {{ $questions->count() }} Soal Terjawab
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('student.test.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-times me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="fas fa-paper-plane me-1"></i>Kirim Jawaban
                </button>
            </div>
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('testForm');
        const answeredCountEl = document.getElementById('answeredCount');
        const submitBtn = document.getElementById('submitBtn');
        const totalQuestions = {{ $questions->count() }};
        
        function updateProgress() {
            const answered = document.querySelectorAll('input[type="radio"]:checked').length;
            answeredCountEl.textContent = answered;
            
            if (answered === totalQuestions) {
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-success');
                submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Semua Terjawab - Kirim Sekarang';
            } else {
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-primary');
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Kirim Jawaban';
            }
        }
        
        // Update progress saat radio button diubah
        document.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', updateProgress);
        });
        
        // Initial progress
        updateProgress();
        
        // Validasi sebelum submit
        form.addEventListener('submit', function(e) {
            const answered = document.querySelectorAll('input[type="radio"]:checked').length;
            if (answered < totalQuestions) {
                e.preventDefault();
                if (confirm(`Anda baru menjawab ${answered} dari ${totalQuestions} soal. Apakah Anda yakin ingin mengirim jawaban?`)) {
                    form.submit();
                }
            }
        });
    });
    </script>
</body>
</html>

