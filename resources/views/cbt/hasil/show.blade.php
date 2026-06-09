@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar"></i> Hasil Try Out: {{ $tryOut->judul }}
            </h1>
            <small class="text-muted">{{ $tryOut->mapel->nama }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('hasil-try-out.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('hasil-try-out.export', $tryOut->id) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Peserta</h6>
                    <h2 class="text-primary">{{ $statistik->jumlah_peserta ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Rata-rata Skor</h6>
                    <h2 class="text-info">{{ number_format($statistik->rata_rata_skor ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Lulus</h6>
                    <h2 class="text-success">{{ $statistik->jumlah_lulus ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Belum Lulus</h6>
                    <h2 class="text-danger">{{ $statistik->jumlah_belum_lulus ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Distribusi Nilai</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartDistribusi"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Kelulusan vs Tidak Lulus</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartKelulusan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking Siswa -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="fas fa-trophy"></i> Ranking Siswa
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="10%">Rank</th>
                        <th width="15%">NIS</th>
                        <th width="30%">Nama</th>
                        <th width="15%">Skor</th>
                        <th width="10%">Nilai</th>
                        <th width="20%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ranking as $hasil)
                        <tr>
                            <td>
                                @if($hasil->ranking === 1)
                                    <span class="badge bg-warning" style="font-size: 1rem;">
                                        <i class="fas fa-medal"></i> {{ $hasil->ranking }}
                                    </span>
                                @elseif($hasil->ranking === 2)
                                    <span class="badge bg-secondary" style="font-size: 1rem;">
                                        <i class="fas fa-medal"></i> {{ $hasil->ranking }}
                                    </span>
                                @elseif($hasil->ranking === 3)
                                    <span class="badge" style="background-color: #CD7F32; font-size: 1rem;">
                                        <i class="fas fa-medal"></i> {{ $hasil->ranking }}
                                    </span>
                                @else
                                    <strong>{{ $hasil->ranking }}</strong>
                                @endif
                            </td>
                            <td>{{ $hasil->siswa->nis ?? '-' }}</td>
                            <td>
                                <strong>{{ $hasil->siswa->nama ?? '-' }}</strong>
                            </td>
                            <td>
                                <h6 class="mb-0">{{ number_format($hasil->skor_akhir, 2) }}</h6>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $hasil->nilai_huruf }}</span>
                            </td>
                            <td>
                                @if($hasil->status_kelulusan === 'lulus')
                                    <span class="badge bg-success">LULUS</span>
                                @else
                                    <span class="badge bg-danger">BELUM LULUS</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('hasil-try-out.show-siswa', $hasil->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada siswa yang menyelesaikan ujian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Fetch statistik chart
fetch('{{ route("hasil-try-out.statistik-chart", $tryOut->id) }}')
    .then(response => response.json())
    .then(data => {
        // Chart Distribusi Nilai
        const ctxDistribusi = document.getElementById('chartDistribusi').getContext('2d');
        new Chart(ctxDistribusi, {
            type: 'bar',
            data: {
                labels: Object.keys(data.distribusi_nilai),
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: Object.values(data.distribusi_nilai),
                    backgroundColor: [
                        '#dc3545',
                        '#ff6c6c',
                        '#ffc107',
                        '#28a745',
                        '#198754'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Chart Kelulusan
        const ctxKelulusan = document.getElementById('chartKelulusan').getContext('2d');
        new Chart(ctxKelulusan, {
            type: 'doughnut',
            data: {
                labels: ['Lulus', 'Belum Lulus'],
                datasets: [{
                    data: [data.lulus, data.belum_lulus],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endsection
