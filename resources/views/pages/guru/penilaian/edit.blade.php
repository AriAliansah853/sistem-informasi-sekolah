@extends('layouts.main')

@section('title', 'Edit Penilaian Siswa')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Edit Penilaian Siswa</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="card-header">
                <h5>Ubah Nilai</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('penilaian.update', $penilaian) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Siswa</label>
                            <input type="text" class="form-control" value="{{ $penilaian->siswa->nama }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Semester</label>
                            <input type="text" class="form-control" value="{{ $penilaian->semester }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <input type="text" class="form-control" value="{{ $penilaian->tahun }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mapel</label>
                            <input type="text" class="form-control" value="{{ $penilaian->mapel->nama_mapel }}" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-2">
                            <label class="form-label">Harian</label>
                            <input type="number" name="nilai_harian" class="form-control" min="0" max="100" value="{{ old('nilai_harian', $penilaian->nilai_harian) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tugas</label>
                            <input type="number" name="nilai_tugas" class="form-control" min="0" max="100" value="{{ old('nilai_tugas', $penilaian->nilai_tugas) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">UTS</label>
                            <input type="number" name="nilai_uts" class="form-control" min="0" max="100" value="{{ old('nilai_uts', $penilaian->nilai_uts) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">UAS</label>
                            <input type="number" name="nilai_uas" class="form-control" min="0" max="100" value="{{ old('nilai_uas', $penilaian->nilai_uas) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sikap</label>
                            <input type="number" name="nilai_sikap" class="form-control" min="0" max="100" value="{{ old('nilai_sikap', $penilaian->nilai_sikap) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kehadiran</label>
                            <input type="number" name="nilai_kehadiran" class="form-control" min="0" max="100" value="{{ old('nilai_kehadiran', $penilaian->nilai_kehadiran) }}">
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-2">
                            <label class="form-label">Bobot Harian</label>
                            <input type="number" name="bobot_harian" class="form-control" min="0" max="100" value="{{ old('bobot_harian', $penilaian->bobot_harian) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Bobot Tugas</label>
                            <input type="number" name="bobot_tugas" class="form-control" min="0" max="100" value="{{ old('bobot_tugas', $penilaian->bobot_tugas) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Bobot UTS</label>
                            <input type="number" name="bobot_uts" class="form-control" min="0" max="100" value="{{ old('bobot_uts', $penilaian->bobot_uts) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Bobot UAS</label>
                            <input type="number" name="bobot_uas" class="form-control" min="0" max="100" value="{{ old('bobot_uas', $penilaian->bobot_uas) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Bobot Sikap</label>
                            <input type="number" name="bobot_sikap" class="form-control" min="0" max="100" value="{{ old('bobot_sikap', $penilaian->bobot_sikap) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Bobot Kehadiran</label>
                            <input type="number" name="bobot_kehadiran" class="form-control" min="0" max="100" value="{{ old('bobot_kehadiran', $penilaian->bobot_kehadiran) }}">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">Perbarui Penilaian</button>
                        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
