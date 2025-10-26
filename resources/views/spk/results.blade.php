@extends('layouts.app')

@section('title', 'Hasil SPK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-bar me-2"></i>Hasil SPK</h2>
    <div>
        <a href="{{ route('spk.index') }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <button class="btn btn-primary" onclick="printResults()">
            <i class="fas fa-print me-1"></i>Cetak
        </button>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-flask fa-3x text-success mb-3"></i>
                <h3 class="text-success">{{ $ipaCount }}</h3>
                <p class="text-muted mb-0">Rekomendasi IPA</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-book fa-3x text-info mb-3"></i>
                <h3 class="text-info">{{ $ipsCount }}</h3>
                <p class="text-muted mb-0">Rekomendasi IPS</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribusi Jurusan</h5>
            </div>
            <div class="card-body">
                <canvas id="majorChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top 10 Ranking</h5>
            </div>
            <div class="card-body">
                <canvas id="rankingChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Results Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Hasil Perhitungan SPK</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="resultsTable">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Rekomendasi</th>
                                <th>Skor SAW</th>
                                <th>Skor VIKOR</th>
                                <th>Skor Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                            <tr>
                                <td>
                                    <span class="badge bg-primary fs-6">#{{ $result->rank }}</span>
                                </td>
                                <td>{{ $result->student->name }}</td>
                                <td>{{ $result->student->nisn }}</td>
                                <td>{{ $result->student->class }}</td>
                                <td>
                                    <span class="badge {{ $result->recommended_major == 'IPA' ? 'badge-ipa' : 'badge-ips' }}">
                                        {{ $result->recommended_major }}
                                    </span>
                                </td>
                                <td>{{ number_format($result->saw_score, 4) }}</td>
                                <td>{{ number_format($result->vikor_score, 4) }}</td>
                                <td>
                                    <strong>{{ number_format($result->final_score, 4) }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('spk.detail', $result->student_id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Chart untuk distribusi jurusan
const ctx1 = document.getElementById('majorChart').getContext('2d');
const majorChart = new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['IPA', 'IPS'],
        datasets: [{
            data: [{{ $ipaCount }}, {{ $ipsCount }}],
            backgroundColor: [
                '#28a745',
                '#17a2b8'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Chart untuk ranking
const ctx2 = document.getElementById('rankingChart').getContext('2d');
const rankingData = @json($results->take(10)->map(function($r) { 
    return ['name' => $r->student->name, 'score' => $r->final_score]; 
}));

const rankingChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: rankingData.map(item => item.name.substring(0, 10) + '...'),
        datasets: [{
            label: 'Skor Akhir',
            data: rankingData.map(item => item.score),
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Fungsi untuk cetak
function printResults() {
    window.print();
}

// DataTables untuk tabel
$(document).ready(function() {
    $('#resultsTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
        }
    });
});
</script>
@endsection

