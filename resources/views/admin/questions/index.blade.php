@extends('layouts.app')

@section('title', 'Kelola Soal Ujian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-question-circle me-2"></i>Kelola Soal Ujian</h2>
    <div>
        <a href="{{ route('admin.questions.create', ['subject' => $subject]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Soal
        </a>
    </div>
</div>

<!-- Filter Subtest -->
<div class="card mb-4">
    <div class="card-body">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.questions.index', ['subject' => 'mathematics']) }}" 
               class="btn {{ $subject === 'mathematics' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="fas fa-calculator me-1"></i>Matematika
            </a>
            <a href="{{ route('admin.questions.index', ['subject' => 'indonesian']) }}" 
               class="btn {{ $subject === 'indonesian' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-book me-1"></i>Bahasa Indonesia
            </a>
            <a href="{{ route('admin.questions.index', ['subject' => 'english']) }}" 
               class="btn {{ $subject === 'english' ? 'btn-info' : 'btn-outline-info' }}">
                <i class="fas fa-language me-1"></i>Bahasa Inggris
            </a>
            <a href="{{ route('admin.questions.index', ['subject' => 'science']) }}" 
               class="btn {{ $subject === 'science' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="fas fa-flask me-1"></i>IPA
            </a>
            <a href="{{ route('admin.questions.index', ['subject' => 'social_studies']) }}" 
               class="btn {{ $subject === 'social_studies' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="fas fa-globe me-1"></i>IPS
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Daftar Soal - {{ $subjects[$subject] }}
            <span class="badge bg-primary ms-2">{{ $questions->total() }} Soal</span>
        </h5>
    </div>
    <div class="card-body">
        @if($questions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Pertanyaan</th>
                            <th width="15%">Jawaban Benar</th>
                            <th width="10%">Bobot</th>
                            <th width="10%">Urutan</th>
                            <th width="10%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $index => $question)
                        <tr>
                            <td>{{ $questions->firstItem() + $index }}</td>
                            <td>
                                <div class="text-truncate" style="max-width: 400px;" title="{{ $question->question }}">
                                    {{ $question->question }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ strtoupper($question->correct_answer) }}</span>
                            </td>
                            <td>{{ $question->points }} poin</td>
                            <td>{{ $question->order }}</td>
                            <td>
                                @if($question->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.questions.edit', $question->id) }}" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.questions.destroy', $question->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $questions->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada soal untuk subtest <strong>{{ $subjects[$subject] }}</strong>.
                <a href="{{ route('admin.questions.create', ['subject' => $subject]) }}" class="btn btn-primary btn-sm ms-2">
                    <i class="fas fa-plus me-1"></i>Tambah Soal Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

