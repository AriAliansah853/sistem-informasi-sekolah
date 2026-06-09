@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line"></i> Hasil Ujian
            </h1>
            <small class="text-muted">Monitor hasil ujian online siswa</small>
        </div>
    </div>

    <div class="row g-4">
        @forelse($tryOuts as $tryOut)
            @php
                $statistik = $tryOut->statistik;
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">{{ $tryOut->judul }}</h5>
                        <small class="text-muted">{{ $tryOut->mapel->nama }}</small>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <h4 class="text-primary">{{ $statistik->jumlah_peserta ?? 0 }}</h4>
                                <small class="text-muted">Peserta</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ $statistik->jumlah_selesai ?? 0 }}</h4>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>

                        <hr>

                        <p class="mb-2">
                            <strong>Rata-rata:</strong> {{ number_format($statistik->rata_rata_skor ?? 0, 2) }}
                        </p>
                        <p class="mb-2">
                            <strong>Tertinggi:</strong> {{ number_format($statistik->skor_tertinggi ?? 0, 2) }}
                        </p>
                        <p class="mb-2">
                            <strong>Terendah:</strong> {{ number_format($statistik->skor_terendah ?? 0, 2) }}
                        </p>
                        <p class="mb-0">
                            <span class="badge bg-success me-2">Lulus: {{ $statistik->jumlah_lulus ?? 0 }}</span>
                            <span class="badge bg-danger">Belum Lulus: {{ $statistik->jumlah_belum_lulus ?? 0 }}</span>
                        </p>
                    </div>
                    <div class="card-footer bg-light">
                        <a href="{{ route('hasil-try-out.show', $tryOut->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('hasil-try-out.export', $tryOut->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Export
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                    <p class="mt-3">Tidak ada hasil ujian</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
