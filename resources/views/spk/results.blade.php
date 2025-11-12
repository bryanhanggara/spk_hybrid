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
    @foreach($majorStats as $stat)
    <div class="col-6 col-md-4 col-lg-2 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                @if($stat['code'] == 'TKR')
                    <i class="fas fa-car fa-2x text-primary mb-2"></i>
                @elseif($stat['code'] == 'TSM')
                    <i class="fas fa-motorcycle fa-2x text-success mb-2"></i>
                @elseif($stat['code'] == 'TKJ')
                    <i class="fas fa-laptop-code fa-2x text-info mb-2"></i>
                @elseif($stat['code'] == 'AP')
                    <i class="fas fa-briefcase fa-2x text-warning mb-2"></i>
                @elseif($stat['code'] == 'AK')
                    <i class="fas fa-calculator fa-2x text-danger mb-2"></i>
                @endif
                <h4 class="mb-1">{{ $stat['count'] }}</h4>
                <p class="text-muted mb-0 small fw-bold">{{ $stat['code'] }}</p>
                <p class="text-muted mb-0" style="font-size: 0.7rem;">{{ Str::limit($stat['name'], 20) }}</p>
            </div>
        </div>
    </div>
    @endforeach
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
                                <th>Pilihan Siswa</th>
                                <th>Rekomendasi</th>
                                <th>Skor SAW</th>
                                <th>Skor VIKOR</th>
                                <th>Skor Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                            @php
                                $student = $result->student;
                                $matches = false;
                                if ($student->major_choice_1 && $result->recommended_major == $student->major_choice_1) {
                                    $matches = true;
                                } elseif ($student->major_choice_2 && $result->recommended_major == $student->major_choice_2) {
                                    $matches = true;
                                }
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-primary fs-6">#{{ $result->rank }}</span>
                                </td>
                                <td>{{ $result->student->name }}</td>
                                <td>{{ $result->student->nisn }}</td>
                                <td>{{ $result->student->class }}</td>
                                <td>
                                    @if($student->major_choice_1 || $student->major_choice_2)
                                        <div>
                                            @if($student->major_choice_1)
                                                <span class="badge bg-primary">1. {{ $student->major_choice_1 }}</span>
                                            @endif
                                            @if($student->major_choice_2)
                                                <span class="badge bg-secondary">2. {{ $student->major_choice_2 }}</span>
                                            @endif
                                        </div>
                                        @if($matches)
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Cocok</small>
                                        @else
                                            <small class="text-warning"><i class="fas fa-info-circle"></i> Berbeda</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        if($result->recommended_major == 'TKR') $badgeClass = 'bg-primary';
                                        elseif($result->recommended_major == 'TSM') $badgeClass = 'bg-success';
                                        elseif($result->recommended_major == 'TKJ') $badgeClass = 'bg-info';
                                        elseif($result->recommended_major == 'AP') $badgeClass = 'bg-warning';
                                        elseif($result->recommended_major == 'AK') $badgeClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $result->recommended_major }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $majorStats[$result->recommended_major]['name'] ?? '' }}</small>
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
const majorChartData = @json($majorStats);
const majorChart = new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: Object.values(majorChartData).map(stat => stat.code + ' - ' + stat.name),
        datasets: [{
            data: Object.values(majorChartData).map(stat => stat.count),
            backgroundColor: [
                '#007bff', // TKR - Blue
                '#28a745', // TSM - Green
                '#17a2b8', // TKJ - Cyan
                '#ffc107', // AP - Yellow
                '#dc3545'  // AK - Red
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
                position: 'bottom',
                labels: {
                    font: {
                        size: 10
                    }
                }
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

