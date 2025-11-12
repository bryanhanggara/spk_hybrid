@extends('layouts.app')

@section('title', 'Tambah Soal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus-circle me-2"></i>Tambah Soal</h2>
    <a href="{{ route('admin.questions.index', ['subject' => $subject]) }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Form Tambah Soal - {{ $subjects[$subject] }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="subject" value="{{ $subject }}">
                    
                    <div class="mb-3">
                        <label for="question" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question') is-invalid @enderror" 
                                  id="question" 
                                  name="question" 
                                  rows="3" 
                                  required>{{ old('question') }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="option_a" class="form-label">
                                <span class="badge bg-danger me-2">A</span>Opsi A <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('option_a') is-invalid @enderror" 
                                   id="option_a" 
                                   name="option_a" 
                                   value="{{ old('option_a') }}" 
                                   required>
                            @error('option_a')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="option_b" class="form-label">
                                <span class="badge bg-warning me-2">B</span>Opsi B <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('option_b') is-invalid @enderror" 
                                   id="option_b" 
                                   name="option_b" 
                                   value="{{ old('option_b') }}" 
                                   required>
                            @error('option_b')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="option_c" class="form-label">
                                <span class="badge bg-info me-2">C</span>Opsi C <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('option_c') is-invalid @enderror" 
                                   id="option_c" 
                                   name="option_c" 
                                   value="{{ old('option_c') }}" 
                                   required>
                            @error('option_c')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="option_d" class="form-label">
                                <span class="badge bg-success me-2">D</span>Opsi D <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('option_d') is-invalid @enderror" 
                                   id="option_d" 
                                   name="option_d" 
                                   value="{{ old('option_d') }}" 
                                   required>
                            @error('option_d')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="correct_answer" class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                            <select class="form-select @error('correct_answer') is-invalid @enderror" 
                                    id="correct_answer" 
                                    name="correct_answer" 
                                    required>
                                <option value="">Pilih Jawaban</option>
                                <option value="a" {{ old('correct_answer') === 'a' ? 'selected' : '' }}>A</option>
                                <option value="b" {{ old('correct_answer') === 'b' ? 'selected' : '' }}>B</option>
                                <option value="c" {{ old('correct_answer') === 'c' ? 'selected' : '' }}>C</option>
                                <option value="d" {{ old('correct_answer') === 'd' ? 'selected' : '' }}>D</option>
                            </select>
                            @error('correct_answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="points" class="form-label">Bobot Nilai</label>
                            <input type="number" 
                                   class="form-control @error('points') is-invalid @enderror" 
                                   id="points" 
                                   name="points" 
                                   value="{{ old('points', 1) }}" 
                                   min="1" 
                                   max="10">
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Default: 1 poin</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="order" class="form-label">Urutan</label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', 0) }}" 
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Default: 0</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-1"></i>Simpan Soal
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
                <p class="text-muted small">
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    <strong>Tips:</strong>
                </p>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Buat pertanyaan yang jelas dan mudah dipahami
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Pastikan hanya ada satu jawaban benar
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Gunakan urutan untuk mengatur tampilan soal
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

