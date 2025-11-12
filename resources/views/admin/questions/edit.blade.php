@extends('layouts.app')

@section('title', 'Edit Soal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Soal</h2>
    <a href="{{ route('admin.questions.index', ['subject' => $question->subject]) }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Soal - {{ $subjects[$question->subject] }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subtest <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject') is-invalid @enderror" 
                                id="subject" 
                                name="subject" 
                                required>
                            @foreach($subjects as $key => $name)
                                <option value="{{ $key }}" {{ old('subject', $question->subject) === $key ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="question" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('question') is-invalid @enderror" 
                                  id="question" 
                                  name="question" 
                                  rows="3" 
                                  required>{{ old('question', $question->question) }}</textarea>
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
                                   value="{{ old('option_a', $question->option_a) }}" 
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
                                   value="{{ old('option_b', $question->option_b) }}" 
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
                                   value="{{ old('option_c', $question->option_c) }}" 
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
                                   value="{{ old('option_d', $question->option_d) }}" 
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
                                <option value="a" {{ old('correct_answer', $question->correct_answer) === 'a' ? 'selected' : '' }}>A</option>
                                <option value="b" {{ old('correct_answer', $question->correct_answer) === 'b' ? 'selected' : '' }}>B</option>
                                <option value="c" {{ old('correct_answer', $question->correct_answer) === 'c' ? 'selected' : '' }}>C</option>
                                <option value="d" {{ old('correct_answer', $question->correct_answer) === 'd' ? 'selected' : '' }}>D</option>
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
                                   value="{{ old('points', $question->points) }}" 
                                   min="1" 
                                   max="10">
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="order" class="form-label">Urutan</label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', $question->order) }}" 
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktifkan Soal
                            </label>
                        </div>
                        <small class="text-muted">Soal yang nonaktif tidak akan muncul dalam ujian</small>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-1"></i>Update Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

