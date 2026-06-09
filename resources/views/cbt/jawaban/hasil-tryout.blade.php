@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar"></i> Hasil Try Out: {{ $hasilTryOut->tryOut->judul }}
            </h1>
        </div>
    </div>

    <!-- Kartu Hasil Utama -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Skor Akhir</h6>
                    <h2 style="color: @if($hasilTryOut->status_kelulusan === 'lulus') #28a745 @else #dc3545 @endif">
                        {{ number_format($hasilTryOut->skor_akhir, 2) }}
                    </h2>
                    <p class="text-muted mb-0">
                        @if($hasilTryOut->status_kelulusan === 'lulus')
                            <span class="badge bg-success">LULUS</span>
                        @else
                            <span class="badge bg-danger">BELUM LULUS</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Nilai Huruf</h6>
                    <h2>{{ $hasilTryOut->nilai_huruf ?? '-' }}</h2>
                    <p class="text-muted mb-0">KKM: {{ $hasilTryOut->tryOut->passing_grade }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Waktu Pengerjaan</h6>
                    <h5>
                        @php
                            $durasi = $hasilTryOut->durasi_pengerjaan_detik;
                            $jam = floor($durasi / 3600);
                            $menit = floor(($durasi % 3600) / 60);
                            $detik = $durasi % 60;
                        @endphp
                        {{ str_pad($jam, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($menit, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($detik, 2, '0', STR_PAD_LEFT) }}
                    </h5>
                    <p class="text-muted mb-0">dari {{ $hasilTryOut->tryOut->durasi_menit }} menit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Performa</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success">{{ $hasilTryOut->jumlah_benar }}</h5>
                            <small class="text-muted">Benar</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-danger">{{ $hasilTryOut->jumlah_salah }}</h5>
                            <small class="text-muted">Salah</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <h5 class="text-warning">{{ $hasilTryOut->jumlah_kosong }}</h5>
                    <small class="text-muted">Kosong</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Jawaban -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="fas fa-list-check"></i> Detail Jawaban
            </h5>
        </div>
        <div class="card-body">
            @foreach($hasilTryOut->jawabanSiswas as $index => $jawaban)
                @php
                    $soal = $jawaban->bankSoal;
                    $benar = $jawaban->is_correct;
                @endphp
                <div class="card mb-3 @if($benar) border-success @elseif($jawaban->status === 'belum_dijawab') border-warning @else border-danger @endif">
                    <div class="card-header py-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <strong>Soal {{ $index + 1 }}</strong>
                                @if($benar)
                                    <span class="badge bg-success ms-2"><i class="fas fa-check"></i> Benar</span>
                                @elseif($jawaban->status === 'belum_dijawab')
                                    <span class="badge bg-warning text-dark ms-2"><i class="fas fa-minus"></i> Tidak Dijawab</span>
                                @else
                                    <span class="badge bg-danger ms-2"><i class="fas fa-times"></i> Salah</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6>{{ $soal->judul }}</h6>
                        <p class="text-muted">{{ $soal->isi }}</p>

                        @if($soal->tipe === 'pilihan_ganda' || $soal->tipe === 'benar_salah')
                            <h6 class="mt-3">Pilihan Jawaban:</h6>
                            <div class="row">
                                @foreach($soal->opsiSoal as $opsi)
                                    @php
                                        $isJawaban = $jawaban->opsi_soal_id === $opsi->id;
                                        $isBenar = $opsi->is_correct;
                                    @endphp
                                    <div class="col-md-6 mb-2">
                                        <div class="border rounded p-2 @if($isBenar) border-success bg-success bg-opacity-10 @elseif($isJawaban && !$benar) border-danger bg-danger bg-opacity-10 @endif">
                                            <strong>{{ $opsi->kode }}.</strong> {{ $opsi->teks }}
                                            @if($isBenar && !$isJawaban)
                                                <br><small class="text-success"><i class="fas fa-check"></i> Jawaban Benar</small>
                                            @endif
                                            @if($isJawaban && $benar)
                                                <br><small class="text-success"><i class="fas fa-check"></i> Jawaban Anda Benar</small>
                                            @elseif($isJawaban && !$benar)
                                                <br><small class="text-danger"><i class="fas fa-times"></i> Jawaban Anda (Salah)</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($soal->tipe === 'essay')
                            <h6 class="mt-3">Jawaban Anda:</h6>
                            <p class="border rounded p-2 bg-light">{{ $jawaban->jawaban ?: '(Tidak dijawab)' }}</p>
                        @endif

                        @if($soal->pembahasan && $hasilTryOut->tryOut->show_pembahasan_langsung)
                            <hr>
                            <h6>Pembahasan:</h6>
                            <p class="text-muted">{{ $soal->pembahasan->pembahasan }}</p>
                            @if($soal->pembahasan->tips_dan_trik)
                                <p class="bg-info bg-opacity-10 p-2 rounded">
                                    <strong>Tips:</strong> {{ $soal->pembahasan->tips_dan_trik }}
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-4">
        <a href="{{ route('try-out-jawaban.index-siswa') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Try Out
        </a>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}
</style>
@endsection
