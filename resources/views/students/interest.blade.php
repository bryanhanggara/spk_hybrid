@extends('layouts.app')

@section('title', 'Angket Minat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>Angket Minat</h2>
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Angket Minat - {{ $student->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.interest.store', $student->id) }}" method="POST">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Petunjuk:</strong> Pilih skor untuk setiap pertanyaan dengan skala 1-5, 
                        dimana 1 = Sangat Tidak Setuju, 2 = Tidak Setuju, 3 = Netral, 4 = Setuju, 5 = Sangat Setuju.
                    </div>
                    
                    @php
                        // Bagian A - Kemampuan & Ketertarikan Akademik (Q1-Q10)
                        // Bagian B - Gaya Belajar & Perilaku (Q11-Q20)
                        $questions = [
                            // Bagian A
                            1 => 'Saya suka kegiatan yang melibatkan perhitungan dan logika matematika',
                            2 => 'Saya menikmati melakukan percobaan atau mencoba hal baru secara praktis',
                            3 => 'Saya tertarik dengan ilmu pengetahuan alam dan teknologi modern',
                            4 => 'Saya suka menganalisis masalah dan mencari solusi yang logis',
                            5 => 'Saya senang mempelajari fisika atau prinsip kerja alat dan mesin',
                            6 => 'Saya suka mempelajari sejarah dan budaya',
                            7 => 'Saya suka bekerja sama dan berinteraksi dengan banyak orang',
                            8 => 'Saya tertarik dengan bisnis, administrasi, atau kegiatan keuangan',
                            9 => 'Saya mengikuti berita sosial, hukum, atau politik',
                            10 => 'Saya suka belajar bahasa dan berkomunikasi secara efektif',
                            
                            // Bagian B
                            11 => 'Saya lebih suka memperbaiki sesuatu yang rusak daripada membuat sesuatu yang baru',
                            12 => 'Saya suka mengutak-atik komputer, aplikasi, atau jaringan internet',
                            13 => 'Saya lebih mudah memahami informasi dalam bentuk angka atau tabel dibandingkan kata-kata',
                            14 => 'Saya senang membantu orang lain mengatur pekerjaan atau dokumen',
                            15 => 'Saya suka mempelajari bagaimana mesin atau alat bekerja',
                            16 => 'Saya merasa puas kalau pekerjaan saya rapi dan terorganisir',
                            17 => 'Saya sering memikirkan cara meningkatkan efisiensi kerja atau memperbaiki kesalahan sistem',
                            18 => 'Saya suka berbicara dan menjelaskan sesuatu kepada orang lain',
                            19 => 'Saya merasa lebih nyaman bekerja dengan alat dan mesin daripada dengan dokumen atau komputer',
                            20 => 'Saya tertarik dengan pekerjaan yang membutuhkan ketelitian tinggi dan tanggung jawab besar'
                        ];
                        
                        $existingAnswers = [];
                        if($student->interestSurvey) {
                            $existingAnswers = $student->interestSurvey->answers ?? [];
                        }
                    @endphp
                    
                    <div class="alert alert-primary">
                        <h6><i class="fas fa-info-circle me-2"></i>Bagian A: Kemampuan & Ketertarikan Akademik</h6>
                        <small>Pertanyaan 1-10</small>
                    </div>
                    
                    @foreach($questions as $num => $question)
                        @if($num == 11)
                            <div class="alert alert-success mt-4">
                                <h6><i class="fas fa-info-circle me-2"></i>Bagian B: Gaya Belajar & Perilaku</h6>
                                <small>Pertanyaan 11-20</small>
                            </div>
                        @endif
                        <div class="mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <span class="badge {{ $num <= 10 ? 'bg-primary' : 'bg-success' }} me-2">{{ $num }}</span>
                                        {{ $question }}
                                    </h6>
                                    
                                    <div class="btn-group w-100 mt-3" role="group">
                                        @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" 
                                               class="btn-check" 
                                               name="answers[{{ $num - 1 }}]" 
                                               id="q{{ $num }}_option{{ $i }}" 
                                               value="{{ $i }}"
                                               {{ old("answers.$num", $existingAnswers[$num-1] ?? '') == $i ? 'checked' : '' }}
                                               required>
                                        <label class="btn btn-outline-{{ $num <= 10 ? 'primary' : 'success' }}" for="q{{ $num }}_option{{ $i }}">
                                            {{ $i }}
                                        </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Summary Card -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <h6 class="text-muted mb-2">Total Skor</h6>
                                    <h2 class="text-primary mb-0" id="totalScore">0</h2>
                                    <small class="text-muted">dari 100 maksimal (20 pertanyaan × 5)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @error('answers')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-1"></i>Simpan Angket Minat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Skala Penilaian</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li><span class="badge bg-danger me-2">1</span>Sangat Tidak Setuju</li>
                    <li><span class="badge bg-warning me-2">2</span>Tidak Setuju</li>
                    <li><span class="badge bg-secondary me-2">3</span>Netral</li>
                    <li><span class="badge bg-info me-2">4</span>Setuju</li>
                    <li><span class="badge bg-success me-2">5</span>Sangat Setuju</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Interpretasi Skor</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <span class="badge bg-success me-2">70-100</span>
                        <strong>Minat Tinggi</strong>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-warning me-2">50-69</span>
                        <strong>Minat Sedang</strong>
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-danger me-2">20-49</span>
                        <strong>Minat Rendah</strong>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-percentage me-2"></i>Bobot dalam SPK</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Hasil angket minat memiliki bobot 30% dalam perhitungan SPK.
                </p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Jawab dengan jujur sesuai minat dan bakat Anda.
                </p>
                <p class="text-muted small mb-0">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Jawaban yang objektif akan menghasilkan rekomendasi yang tepat.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
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
        if (total >= 70) {
            totalDisplay.className = 'text-success';
        } else if (total >= 50) {
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
    
    // Add visual feedback for selected options
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Remove all active classes from labels in same group
            const labels = this.closest('.btn-group').querySelectorAll('label');
            labels.forEach(label => {
                label.classList.remove('active');
            });
            
            // Add active class to checked label
            const checkedLabel = this.nextElementSibling;
            if (checkedLabel) {
                checkedLabel.classList.add('active');
            }
        });
    });
});
</script>
@endsection

