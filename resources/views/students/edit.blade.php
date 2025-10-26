@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i>Edit Data Siswa</h2>
    <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Form Edit Siswa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $student->name) }}" 
                               placeholder="Masukkan nama lengkap siswa">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nisn" class="form-label">
                            <i class="fas fa-id-card me-2"></i>NISN <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('nisn') is-invalid @enderror" 
                               id="nisn" 
                               name="nisn" 
                               value="{{ old('nisn', $student->nisn) }}" 
                               placeholder="Masukkan NISN">
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="class" class="form-label">
                            <i class="fas fa-school me-2"></i>Kelas <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('class') is-invalid @enderror" 
                               id="class" 
                               name="class" 
                               value="{{ old('class', $student->class) }}" 
                               placeholder="Contoh: IX-A">
                        @error('class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-venus-mars me-2"></i>Jenis Kelamin <span class="text-danger">*</span>
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input @error('gender') is-invalid @enderror" 
                                           type="radio" 
                                           name="gender" 
                                           id="gender_male" 
                                           value="L"
                                           {{ old('gender', $student->gender) == 'L' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_male">
                                        <i class="fas fa-mars text-primary me-2"></i>Laki-laki
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input @error('gender') is-invalid @enderror" 
                                           type="radio" 
                                           name="gender" 
                                           id="gender_female" 
                                           value="P"
                                           {{ old('gender', $student->gender) == 'P' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_female">
                                        <i class="fas fa-venus text-danger me-2"></i>Perempuan
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('gender')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Informasi -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Mengubah data siswa tidak akan mempengaruhi nilai akademik, angket minat, dan hasil wawancara yang sudah diinput.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add validation feedback
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[type="text"], input[type="radio"]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const nisn = document.getElementById('nisn').value.trim();
        const classValue = document.getElementById('class').value.trim();
        const gender = document.querySelector('input[name="gender"]:checked');
        
        let isValid = true;
        
        if (!name) {
            document.getElementById('name').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!nisn) {
            document.getElementById('nisn').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!classValue) {
            document.getElementById('class').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!gender) {
            document.querySelectorAll('input[name="gender"]').forEach(radio => {
                radio.closest('.border').classList.add('border-danger');
            });
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection

