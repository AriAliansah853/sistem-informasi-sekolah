@extends('layouts.main')

@section('title', 'Rekap Penilaian Siswa')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Rekap Nilai Siswa</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('penilaian.rekap') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $item)
                                <option value="{{ $item->id }}" {{ request('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>1</option>
                            <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>2</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <input type="text" name="tahun" class="form-control" value="{{ request('tahun', date('Y')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari Siswa</label>
                        <input type="text" name="siswa_id" class="form-control" value="{{ request('siswa_id') }}" placeholder="Masukkan ID Siswa">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary">Tampilkan Rekap</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Semester</th>
                            <th>Tahun</th>
                            <th>Nilai Akhir</th>
                            <th>Rata-rata</th>
                            <th>Ranking</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penilaians as $penilaian)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penilaian->siswa->nama }}</td>
                                <td>{{ $penilaian->kelas->nama_kelas }}</td>
                                <td>{{ $penilaian->mapel->nama_mapel }}</td>
                                <td>{{ $penilaian->semester }}</td>
                                <td>{{ $penilaian->tahun }}</td>
                                <td>{{ $penilaian->nilai_akhir }}</td>
                                <td>{{ $penilaian->nilai_rata_rata }}</td>
                                <td>{{ $ranks[$penilaian->kelas_id][$penilaian->id] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada nilai untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
