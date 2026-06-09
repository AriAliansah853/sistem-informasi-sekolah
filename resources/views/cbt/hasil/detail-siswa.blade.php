@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-graduate"></i> Detail Hasil Siswa
            </h1>
            <small class="text-muted">{{ $hasilTryOut->tryOut->judul }} - {{ $hasilTryOut->siswa->nama }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('hasil-try-out.show', $hasilTryOut->tryOut->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Nama Siswa</h6>
                    <p class="mb-0">{{ $hasilTryOut->siswa->nama }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">NIS</h6>
                    <p class="mb-0">{{ $hasilTryOut->siswa->nis }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Skor Akhir</h6>
                    <h4>{{ number_format($hasilTryOut->skor_akhir, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Status</h6>
                    <span class="badge bg-{{ $hasilTryOut->status_kelulusan === 'lulus' ? 'success' : 'danger' }}">
                        {{ strtoupper($hasilTryOut->status_kelulusan) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Ringkasan</h5>
        </div>
        <div class="card-body row">
            <div class="col-md-4">
                <p class="mb-2"><strong>Jumlah Benar:</strong> {{ $hasilTryOut->jumlah_benar }}</p>
                <p class="mb-2"><strong>Jumlah Salah:</strong> {{ $hasilTryOut->jumlah_salah }}</p>
                <p class="mb-2"><strong>Jumlah Kosong:</strong> {{ $hasilTryOut->jumlah_kosong }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-2"><strong>Durasi (detik):</strong> {{ $hasilTryOut->durasi_pengerjaan_detik }}</p>
                <p class="mb-2"><strong>Nilai Huruf:</strong> {{ $hasilTryOut->nilai_huruf }}</p>
                <p class="mb-2"><strong>Catatan Guru:</strong> {{ $hasilTryOut->catatan_guru ?? '-' }}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-2"><strong>Waktu Mulai:</strong> {{ $hasilTryOut->waktu_mulai?->format('d/m/Y H:i') }}</p>
                <p class="mb-2"><strong>Waktu Selesai:</strong> {{ $hasilTryOut->waktu_selesai?->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Jawaban Siswa</h5>
        </div>
        <div class="card-body">
            @foreach($hasilTryOut->jawabanSiswas as $index => $jawaban)
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Soal {{ $index + 1 }} - {{ $jawaban->bankSoal->judul }}</h6>
                        <p class="text-muted">{{ $jawaban->bankSoal->isi }}</p>

                        @if($jawaban->bankSoal->tipe === 'pilihan_ganda' || $jawaban->bankSoal->tipe === 'benar_salah')
                            <p><strong>Jawaban Siswa:</strong> 
                                {{ $jawaban->opsiSoal?->kode ? $jawaban->opsiSoal->kode . '. ' : '' }}{{ $jawaban->opsiSoal?->teks ?? $jawaban->jawaban ?? '(Tidak dijawab)' }}
                            </p>
                        @else
                            <p><strong>Jawaban Siswa:</strong> {{ $jawaban->jawaban ?? '(Tidak dijawab)' }}</p>
                        @endif

                        @if($jawaban->bankSoal->pembahasan)
                            <div class="mt-3 p-3 rounded bg-light border">
                                <h6>Pembahasan</h6>
                                <p>{{ $jawaban->bankSoal->pembahasan->pembahasan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
