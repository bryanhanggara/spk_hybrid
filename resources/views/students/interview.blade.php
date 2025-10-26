@extends('layouts.app')

@section('title', 'Hasil Wawancara')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-comments me-2"></i>Hasil Wawancara</h2>
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Hasil Wawancara - {{ $student->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.interview.store', $student->id) }}" method="POST">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Berikan penilaian untuk setiap aspek dengan skala 1-5, 
                        dimana 1 = Sangat Kurang, 2 = Kurang, 3 = Cukup, 4 = Baik, 5 = Sangat Baik.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-comments text-primary me-2"></i>
                                            Kemampuan Komunikasi
                                        </h6>
                                        <p class="text-muted small">Kemampuan siswa dalam berkomunikasi dan menyampaikan pendapat</p>
                                        
                                        <div class="btn-group w-100" role="group">
                                            @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check" 
                                                   name="communication_skill" 
                                                   id="comm_option{{ $i }}" 
                                                   value="{{ $i }}"
                                                   {{ old('communication_skill', $student->interviewScore->communication_skill ?? '') == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary" for="comm_option{{ $i }}">
                                                {{ $i }}
                                            </label>
                                            @endfor
                                        </div>
                                        
                                        @error('communication_skill')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-fire text-success me-2"></i>
                                            Motivasi Belajar
                                        </h6>
                                        <p class="text-muted small">Tingkat motivasi dan semangat belajar siswa</p>
                                        
                                        <div class="btn-group w-100" role="group">
                                            @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check" 
                                                   name="motivation" 
                                                   id="mot_option{{ $i }}" 
                                                   value="{{ $i }}"
                                                   {{ old('motivation', $student->interviewScore->motivation ?? '') == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success" for="mot_option{{ $i }}">
                                                {{ $i }}
                                            </label>
                                            @endfor
                                        </div>
                                        
                                        @error('motivation')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-user text-info me-2"></i>
                                            Kepribadian
                                        </h6>
                                        <p class="text-muted small">Sikap, karakter, dan kepribadian siswa</p>
                                        
                                        <div class="btn-group w-100" role="group">
                                            @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check" 
                                                   name="personality" 
                                                   id="per_option{{ $i }}" 
                                                   value="{{ $i }}"
                                                   {{ old('personality', $student->interviewScore->personality ?? '') == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-info" for="per_option{{ $i }}">
                                                {{ $i }}
                                            </label>
                                            @endfor
                                        </div>
                                        
                                        @error('personality')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-brain text-warning me-2"></i>
                                            Potensi Akademik
                                        </h6>
                                        <p class="text-muted small">Kemampuan dan potensi akademik siswa</p>
                                        
                                        <div class="btn-group w-100" role="group">
                                            @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check" 
                                                   name="academic_potential" 
                                                   id="acad_option{{ $i }}" 
                                                   value="{{ $i }}"
                                                   {{ old('academic_potential', $student->interviewScore->academic_potential ?? '') == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning" for="acad_option{{ $i }}">
                                                {{ $i }}
                                            </label>
                                            @endfor
                                        </div>
                                        
                                        @error('academic_potential')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-briefcase text-danger me-2"></i>
                                            Orientasi Karir
                                        </h6>
                                        <p class="text-muted small">Pemahaman dan orientasi karir siswa</p>
                                        
                                        <div class="btn-group w-100" role="group">
                                            @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check" 
                                                   name="career_orientation" 
                                                   id="career_option{{ $i }}" 
                                                   value="{{ $i }}"
                                                   {{ old('career_orientation', $student->interviewScore->career_orientation ?? '') == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="career_option{{ $i }}">
                                                {{ $i }}
                                            </label>
                                            @endfor
                                        </div>
                                        
                                        @error('career_orientation')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">
                                            <i class="fas fa-calculator text-dark me-2"></i>
                                            Total Skor
                                        </h6>
                                        <h3 class="text-primary" id="totalScore">0</h3>
                                        <p class="text-muted small">dari 25 maksimal</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Hasil Wawancara
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
                <h6>Skala Penilaian:</h6>
                <ul class="list-unstyled small">
                    <li><span class="badge bg-danger me-2">1</span>Sangat Kurang</li>
                    <li><span class="badge bg-warning me-2">2</span>Kurang</li>
                    <li><span class="badge bg-secondary me-2">3</span>Cukup</li>
                    <li><span class="badge bg-info me-2">4</span>Baik</li>
                    <li><span class="badge bg-success me-2">5</span>Sangat Baik</li>
                </ul>
                
                <h6 class="mt-3">Aspek Penilaian:</h6>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-comments text-primary me-2"></i>Komunikasi: Kemampuan berkomunikasi</li>
                    <li><i class="fas fa-fire text-success me-2"></i>Motivasi: Semangat belajar</li>
                    <li><i class="fas fa-user text-info me-2"></i>Kepribadian: Sikap dan karakter</li>
                    <li><i class="fas fa-brain text-warning me-2"></i>Potensi: Kemampuan akademik</li>
                    <li><i class="fas fa-briefcase text-danger me-2"></i>Karir: Orientasi masa depan</li>
                </ul>
                
                <h6 class="mt-3">Bobot dalam SPK:</h6>
                <p class="text-muted small">
                    <i class="fas fa-percentage text-primary me-2"></i>
                    Hasil wawancara memiliki bobot 30% dalam perhitungan SPK.
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Berikan penilaian yang objektif berdasarkan observasi selama wawancara.
                </p>
                <p class="text-muted small">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Pertimbangkan aspek keseluruhan, bukan hanya satu momen tertentu.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-calculate total score
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="radio"]');
    const totalDisplay = document.getElementById('totalScore');
    
    function updateTotal() {
        let total = 0;
        inputs.forEach(input => {
            if (input.checked) {
                total += parseInt(input.value);
            }
        });
        
        totalDisplay.textContent = total;
        
        // Change color based on score
        if (total >= 20) {
            totalDisplay.className = 'text-success';
        } else if (total >= 15) {
            totalDisplay.className = 'text-warning';
        } else {
            totalDisplay.className = 'text-danger';
        }
    }
    
    inputs.forEach(input => {
        input.addEventListener('change', updateTotal);
    });
    
    // Initial calculation
    updateTotal();
});
</script>
@endsection

