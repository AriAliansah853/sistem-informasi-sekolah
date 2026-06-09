@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-eye"></i> Detail Soal
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('bank-soal.edit', $bankSoal->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('bank-soal.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">{{ $bankSoal->judul }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong>Mata Pelajaran:</strong> {{ $bankSoal->mapel->nama ?? '-' }}
                    </p>
                    <p class="mb-2">
                        <strong>Tipe Soal:</strong> 
                        <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $bankSoal->tipe)) }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Tingkat Kesulitan:</strong> {{ $bankSoal->tingkat_kesulitan }}/5
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong>Bobot:</strong> {{ $bankSoal->bobot }}
                    </p>
                    <p class="mb-2">
                        <strong>Status:</strong>
                        @if($bankSoal->status === 'published')
                            <span class="badge bg-success">Dipublikasikan</span>
                        @else
                            <span class="badge bg-warning text-dark">Draft</span>
                        @endif
                    </p>
                    <p class="mb-0">
                        <strong>Dibuat oleh:</strong> {{ $bankSoal->guru->user->name ?? '-' }}
                    </p>
                </div>
            </div>

            <hr>

            <h6 class="mb-3"><strong>Soal:</strong></h6>
            <div class="bg-light p-3 rounded mb-4">
                {!! nl2br(e($bankSoal->isi)) !!}
            </div>

            @if($bankSoal->tipe !== 'essay')
                <h6 class="mb-3"><strong>Pilihan Jawaban:</strong></h6>
                <div class="row">
                    @foreach($bankSoal->opsiSoal as $opsi)
                        <div class="col-md-6 mb-3">
                            <div class="card @if($opsi->is_correct) border-success @else border-secondary @endif">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-auto">
                                            <h6 class="mb-0">{{ $opsi->kode }}.</h6>
                                        </div>
                                        <div class="col">
                                            <p class="mb-0">{{ $opsi->teks }}</p>
                                            @if($opsi->is_correct)
                                                <small class="text-success"><i class="fas fa-check"></i> Jawaban Benar</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($bankSoal->pembahasan)
                <hr>
                <h6 class="mb-3"><strong>Pembahasan:</strong></h6>
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($bankSoal->pembahasan)) !!}
                </div>
            @endif

            @if($bankSoal->kata_kunci)
                <div class="mt-3">
                    <strong>Kata Kunci:</strong> {{ $bankSoal->kata_kunci }}
                </div>
            @endif
        </div>
    </div>

    @if($bankSoal->status === 'draft')
        <form action="{{ route('bank-soal.publish', $bankSoal->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Publikasikan Soal
            </button>
        </form>
    @endif
</div>
@endsection
