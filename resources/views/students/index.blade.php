@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Data Siswa</h2>
    <div>
        <a href="{{ route('spk.index') }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-arrow-left me-1"></i>Dashboard
        </a>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tambah Siswa
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Daftar Siswa</h5>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status Data</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                <tr>
                                    <td>{{ $students->firstItem() + $index }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->nisn }}</td>
                                    <td>{{ $student->class }}</td>
                                    <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>
                                        @php
                                            $status = [];
                                            if($student->academicScore) $status[] = 'Akademik';
                                            if($student->interestSurvey) $status[] = 'Angket';
                                            if($student->interviewScore) $status[] = 'Wawancara';
                                        @endphp
                                        
                                        @if(count($status) == 3)
                                            <span class="badge bg-success">Lengkap</span>
                                        @elseif(count($status) > 0)
                                            <span class="badge bg-warning">{{ count($status) }}/3</span>
                                        @else
                                            <span class="badge bg-danger">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('students.show', $student->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student->id) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('students.destroy', $student->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $students->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada data siswa</h5>
                        <p class="text-muted">Silakan tambah data siswa terlebih dahulu</p>
                        <a href="{{ route('students.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Tambah Siswa Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

