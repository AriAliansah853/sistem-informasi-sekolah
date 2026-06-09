@extends('layouts.main')

@section('title', 'Penilaian Siswa')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Penilaian Siswa</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title">Daftar Nilai</h5>
                        <p class="text-muted">Kelola nilai harian, tugas, UTS, UAS, sikap, dan kehadiran.</p>
                    </div>
                    <a href="{{ route('penilaian.create') }}" class="btn btn-primary">Input Nilai Baru</a>
                </div>

                <form action="{{ route('penilaian.index') }}" method="GET" class="row g-3">
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
                        <input type="text" name="tahun" class="form-control" value="{{ request('tahun', date('Y')) }}" placeholder="2026">
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('penilaian.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penilaians as $penilaian)
                                <tr>
                                    <td>{{ $loop->iteration + ($penilaians->currentPage() - 1) * $penilaians->perPage() }}</td>
                                    <td>{{ $penilaian->siswa->nama }}</td>
                                    <td>{{ $penilaian->kelas->nama_kelas }}</td>
                                    <td>{{ $penilaian->mapel->nama_mapel }}</td>
                                    <td>{{ $penilaian->semester }}</td>
                                    <td>{{ $penilaian->tahun }}</td>
                                    <td>{{ $penilaian->nilai_akhir }}</td>
                                    <td>{{ $penilaian->nilai_rata_rata }}</td>
                                    <td>
                                        <a href="{{ route('penilaian.edit', $penilaian) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="{{ route('penilaian.export.student.pdf', ['siswa_id' => $penilaian->siswa_id, 'kelas_id' => $penilaian->kelas_id, 'semester' => $penilaian->semester, 'tahun' => $penilaian->tahun]) }}" target="_blank" class="btn btn-sm btn-success">Cetak Rapor</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data penilaian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $penilaians->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
