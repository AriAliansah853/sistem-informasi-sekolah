@extends('layouts.main')

@section('title', 'Input Penilaian Siswa')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Input Penilaian Siswa</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('penilaian.create') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $item)
                                <option value="{{ $item->id }}" {{ optional($selectedKelas)->id == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select">
                            <option value="1" {{ $semester == 1 ? 'selected' : '' }}>1</option>
                            <option value="2" {{ $semester == 2 ? 'selected' : '' }}>2</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <input type="text" name="tahun" class="form-control" value="{{ $tahun }}" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Muat Daftar Siswa</button>
                    </div>
                </form>
            </div>
        </div>

        @if($selectedKelas)
            <form action="{{ route('penilaian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                <input type="hidden" name="semester" value="{{ $semester }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Bobot Penilaian</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Harian (%)</label>
                                <input type="number" class="form-control" name="bobot_harian" value="{{ old('bobot_harian', 20) }}" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tugas (%)</label>
                                <input type="number" class="form-control" name="bobot_tugas" value="{{ old('bobot_tugas', 20) }}" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">UTS (%)</label>
                                <input type="number" class="form-control" name="bobot_uts" value="{{ old('bobot_uts', 25) }}" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">UAS (%)</label>
                                <input type="number" class="form-control" name="bobot_uas" value="{{ old('bobot_uas', 25) }}" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sikap (%)</label>
                                <input type="number" class="form-control" name="bobot_sikap" value="{{ old('bobot_sikap', 5) }}" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kehadiran (%)</label>
                                <input type="number" class="form-control" name="bobot_kehadiran" value="{{ old('bobot_kehadiran', 5) }}" min="0" max="100">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Input Nilai untuk Kelas {{ $selectedKelas->nama_kelas }}</h5>
                        <p class="text-muted mb-0">Mata Pelajaran: {{ $guru->mapel->nama_mapel }}</p>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Harian</th>
                                    <th>Tugas</th>
                                    <th>UTS</th>
                                    <th>UAS</th>
                                    <th>Sikap</th>
                                    <th>Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswas as $siswa)
                                    @php
                                        $record = $existing->get($siswa->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>
                                            <input type="hidden" name="siswa_id[]" value="{{ $siswa->id }}">
                                            <input type="number" name="nilai_harian[{{ $siswa->id }}]" class="form-control" min="0" max="100" value="{{ old('nilai_harian.' . $siswa->id, $record->nilai_harian ?? '') }}">
                                        </td>
                                        <td><input type="number" name="nilai_tugas[{{ $siswa->id }}]" class="form-control" min="0" max="100" value="{{ old('nilai_tugas.' . $siswa->id, $record->nilai_tugas ?? '') }}"></td>
                                        <td><input type="number" name="nilai_uts[{{ $siswa->id }}]" class="form-control" min="0" max="100" value="{{ old('nilai_uts.' . $siswa->id, $record->nilai_uts ?? '') }}"></td>
                                        <td><input type="number" name="nilai_uas[{{ $siswa->id }}]" class="form-control" min="0" max="100" value="{{ old('nilai_uas.' . $siswa->id, $record->nilai_uas ?? '') }}"></td>
                                        <td><input type="number" name="nilai_sikap[{{ $siswa->id }}]" class="form-control" min="0" max="100" value="{{ old('nilai_sikap.' . $siswa->id, $record->nilai_sikap ?? '') }}"></td>
                                        <td><input type="number" name="nilai_kehadiran[{{ $siswa->id }}]" class="form-control" min="0" max="100" value="{{ old('nilai_kehadiran.' . $siswa->id, $record->nilai_kehadiran ?? '') }}"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success">Simpan Nilai</button>
                    </div>
                </div>
            </form>
        @else
            <div class="card">
                <div class="card-body">
                    <p>Pilih kelas terlebih dahulu untuk memuat daftar siswa.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
