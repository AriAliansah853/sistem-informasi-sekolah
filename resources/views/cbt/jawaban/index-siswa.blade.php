@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 mt-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-file-alt"></i> Try Out Tersedia
            </h1>
            <small class="text-muted">Ikuti ujian online untuk meningkatkan prestasi</small>
        </div>
    </div>

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($tryOuts as $tryOut)
            @php
                $sudahDikerjakan = in_array($tryOut->id, $hasilSiswa);
                $waktuMulai = $tryOut->waktu_mulai;
                $waktuSelesai = $tryOut->waktu_selesai;
                $sekarang = now();
                $bisa_dikerjakan = !$sudahDikerjakan && $sekarang >= $waktuMulai && $sekarang <= $waktuSelesai;
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header @if($sudahDikerjakan) bg-success @elseif($bisa_dikerjakan) bg-primary @else bg-secondary @endif text-white">
                        <h5 class="card-title mb-0">{{ $tryOut->judul }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Mata Pelajaran:</strong> {{ $tryOut->mapel->nama }}
                        </p>
                        <p class="mb-2">
                            <strong>Guru:</strong> {{ $tryOut->guru->user->name }}
                        </p>
                        <p class="mb-2">
                            <strong>Jumlah Soal:</strong> {{ $tryOut->jumlah_soal }}
                        </p>
                        <p class="mb-2">
                            <strong>Durasi:</strong> {{ $tryOut->durasi_menit }} menit
                        </p>
                        <p class="mb-2">
                            <strong>KKM:</strong> {{ $tryOut->passing_grade }}
                        </p>
                        <hr>
                        <p class="mb-0">
                            <small class="text-muted">
                                <strong>Waktu:</strong><br>
                                Mulai: {{ $tryOut->waktu_mulai->format('d/m/Y H:i') }}<br>
                                Selesai: {{ $tryOut->waktu_selesai->format('d/m/Y H:i') }}
                            </small>
                        </p>
                    </div>
                    <div class="card-footer bg-light">
                        @if($sudahDikerjakan)
                            <span class="badge bg-success me-2">
                                <i class="fas fa-check"></i> Sudah Dikerjakan
                            </span>
                            <a href="{{ route('try-out-jawaban.hasil', $tryOut->hasilTryOuts()->where('siswa_id', $siswa->id)->first()->id) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Lihat Hasil
                            </a>
                        @elseif($bisa_dikerjakan)
                            <a href="{{ route('try-out-jawaban.kerjakan', $tryOut->id) }}" 
                               class="btn btn-sm btn-success w-100">
                                <i class="fas fa-play"></i> Mulai Ujian
                            </a>
                        @else
                            <span class="text-muted small">
                                @if($sekarang < $waktuMulai)
                                    <i class="fas fa-lock"></i> Ujian belum dibuka
                                @else
                                    <i class="fas fa-times"></i> Ujian sudah ditutup
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                    <p class="mt-3">Tidak ada try out yang tersedia saat ini</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $tryOuts->links() }}
    </div>
</div>
@endsection
