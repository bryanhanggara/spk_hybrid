@extends('layouts.app')

@section('title', 'Nilai Akademik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book me-2"></i>Nilai Akademik</h2>
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Nilai Akademik - {{ $student->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.academic.store', $student->id) }}" method="POST">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Masukkan nilai siswa untuk setiap mata pelajaran (skala 0-100).
                    </div>
                    
                    <div class="mb-4">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-calculator text-primary me-2"></i>
                                    Matematika
                                </h6>
                                <p class="text-muted small mb-3">Nilai ujian/ulangan mata pelajaran Matematika</p>
                                
                                <input type="number" 
                                       class="form-control form-control-lg @error('mathematics') is-invalid @enderror" 
                                       name="mathematics" 
                                       value="{{ old('mathematics', $student->academicScore->mathematics ?? '') }}" 
                                       min="0" 
                                       max="100" 
                                       step="0.01"
                                       placeholder="Masukkan nilai (0-100)"
                                       required>
                                @error('mathematics')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-book text-success me-2"></i>
                                    Bahasa Indonesia
                                </h6>
                                <p class="text-muted small mb-3">Nilai ujian/ulangan mata pelajaran Bahasa Indonesia</p>
                                
                                <input type="number" 
                                       class="form-control form-control-lg @error('indonesian') is-invalid @enderror" 
                                       name="indonesian" 
                                       value="{{ old('indonesian', $student->academicScore->indonesian ?? '') }}" 
                                       min="0" 
                                       max="100" 
                                       step="0.01"
                                       placeholder="Masukkan nilai (0-100)"
                                       required>
                                @error('indonesian')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="card border-info">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-language text-info me-2"></i>
                                    Bahasa Inggris
                                </h6>
                                <p class="text-muted small mb-3">Nilai ujian/ulangan mata pelajaran Bahasa Inggris</p>
                                
                                <input type="number" 
                                       class="form-control form-control-lg @error('english') is-invalid @enderror" 
                                       name="english" 
                                       value="{{ old('english', $student->academicScore->english ?? '') }}" 
                                       min="0" 
                                       max="100" 
                                       step="0.01"
                                       placeholder="Masukkan nilai (0-100)"
                                       required>
                                @error('english')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="card border-warning">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-flask text-warning me-2"></i>
                                    IPA (Ilmu Pengetahuan Alam)
                                </h6>
                                <p class="text-muted small mb-3">Nilai ujian/ulangan mata pelajaran IPA</p>
                                
                                <input type="number" 
                                       class="form-control form-control-lg @error('science') is-invalid @enderror" 
                                       name="science" 
                                       value="{{ old('science', $student->academicScore->science ?? '') }}" 
                                       min="0" 
                                       max="100" 
                                       step="0.01"
                                       placeholder="Masukkan nilai (0-100)"
                                       required>
                                @error('science')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="card border-danger">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-globe text-danger me-2"></i>
                                    IPS (Ilmu Pengetahuan Sosial)
                                </h6>
                                <p class="text-muted small mb-3">Nilai ujian/ulangan mata pelajaran IPS</p>
                                
                                <input type="number" 
                                       class="form-control form-control-lg @error('social_studies') is-invalid @enderror" 
                                       name="social_studies" 
                                       value="{{ old('social_studies', $student->academicScore->social_studies ?? '') }}" 
                                       min="0" 
                                       max="100" 
                                       step="0.01"
                                       placeholder="Masukkan nilai (0-100)"
                                       required>
                                @error('social_studies')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Card -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <h6 class="text-muted mb-2">Rata-rata Nilai</h6>
                                    <h2 class="text-primary mb-0" id="averageScore">0.00</h2>
                                    <small class="text-muted">dari 100 maksimal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-1"></i>Simpan Nilai Akademik
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
            </div>
            <div class="card-body">
                <h6>Mata Pelajaran:</h6>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-calculator text-primary me-2"></i><strong>Matematika</strong> - Kemampuan berhitung</li>
                    <li><i class="fas fa-book text-success me-2"></i><strong>Bahasa Indonesia</strong> - Komunikasi bahasa</li>
                    <li><i class="fas fa-language text-info me-2"></i><strong>Bahasa Inggris</strong> - Bahasa internasional</li>
                    <li><i class="fas fa-flask text-warning me-2"></i><strong>IPA</strong> - Ilmu eksakta</li>
                    <li><i class="fas fa-globe text-danger me-2"></i><strong>IPS</strong> - Ilmu sosial</li>
                </ul>
                
                <h6 class="mt-3">Bobot dalam SPK:</h6>
                <p class="text-muted small">
                    <i class="fas fa-percentage text-primary me-2"></i>
                    Nilai akademik memiliki bobot 40% dalam perhitungan SPK.
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Gunakan data nilai yang valid dan terbaru.
                </p>
                <p class="text-muted small">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Nilai yang lebih tinggi akan meningkatkan peluang rekomendasi.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="number"]');
    const averageDisplay = document.getElementById('averageScore');
    
    function calculateAverage() {
        let total = 0;
        let count = 0;
        
        inputs.forEach(input => {
            if (input.value) {
                total += parseFloat(input.value) || 0;
                count++;
            }
        });
        
        const average = count > 0 ? (total / count).toFixed(2) : '0.00';
        averageDisplay.textContent = average;
        
        // Change color based on average
        const avgValue = parseFloat(average);
        if (avgValue >= 80) {
            averageDisplay.className = 'text-success';
        } else if (avgValue >= 60) {
            averageDisplay.className = 'text-warning';
        } else {
            averageDisplay.className = 'text-danger';
        }
    }
    
    inputs.forEach(input => {
        input.addEventListener('input', calculateAverage);
    });
    
    // Initial calculation
    calculateAverage();
});
</script>
@endsection

