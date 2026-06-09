@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-circle"></i> Buat Try Out Baru
            </h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('try-out.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Informasi Try Out</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="mapel_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="mapel_id" id="mapel_id" class="form-select @error('mapel_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="kelas_id" class="form-label">Kelas (Opsional)</label>
                        <select name="kelas_id" id="kelas_id" class="form-select">
                            <option value="">-- Untuk Semua Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Try Out <span class="text-danger">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" 
                           value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="waktu_mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" 
                               class="form-control @error('waktu_mulai') is-invalid @enderror" 
                               value="{{ old('waktu_mulai') }}" required>
                        @error('waktu_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="waktu_selesai" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" 
                               class="form-control @error('waktu_selesai') is-invalid @enderror" 
                               value="{{ old('waktu_selesai') }}" required>
                        @error('waktu_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="durasi_menit" class="form-label">Durasi Ujian (Menit) <span class="text-danger">*</span></label>
                        <input type="number" name="durasi_menit" id="durasi_menit" 
                               class="form-control @error('durasi_menit') is-invalid @enderror" 
                               value="{{ old('durasi_menit', 60) }}" min="5" max="480" required>
                        @error('durasi_menit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jumlah_soal" class="form-label">Jumlah Soal <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_soal" id="jumlah_soal" 
                               class="form-control @error('jumlah_soal') is-invalid @enderror" 
                               value="{{ old('jumlah_soal', 10) }}" min="1" required>
                        @error('jumlah_soal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="passing_grade" class="form-label">Passing Grade <span class="text-danger">*</span></label>
                        <input type="number" name="passing_grade" id="passing_grade" 
                               class="form-control @error('passing_grade') is-invalid @enderror" 
                               value="{{ old('passing_grade', 60) }}" min="0" max="100" required>
                        @error('passing_grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengaturan Ujian -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Pengaturan Ujian</h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-3">
                    <input type="checkbox" name="acak_soal" id="acak_soal" class="form-check-input" value="1" 
                           {{ old('acak_soal', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="acak_soal">
                        Acak Urutan Soal
                    </label>
                    <small class="text-muted d-block mt-1">Setiap siswa akan melihat soal dengan urutan berbeda</small>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="acak_opsi" id="acak_opsi" class="form-check-input" value="1" 
                           {{ old('acak_opsi', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="acak_opsi">
                        Acak Pilihan Jawaban
                    </label>
                    <small class="text-muted d-block mt-1">Pilihan jawaban akan diacak untuk setiap siswa</small>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="show_hasil_langsung" id="show_hasil_langsung" class="form-check-input" value="1" 
                           {{ old('show_hasil_langsung') ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_hasil_langsung">
                        Tampilkan Hasil Langsung
                    </label>
                    <small class="text-muted d-block mt-1">Siswa dapat melihat hasil segera setelah selesai ujian</small>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="show_pembahasan_langsung" id="show_pembahasan_langsung" class="form-check-input" value="1" 
                           {{ old('show_pembahasan_langsung') ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_pembahasan_langsung">
                        Tampilkan Pembahasan Langsung
                    </label>
                    <small class="text-muted d-block mt-1">Siswa dapat melihat pembahasan setelah selesai ujian</small>
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Buat Try Out
            </button>
            <a href="{{ route('try-out.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection
