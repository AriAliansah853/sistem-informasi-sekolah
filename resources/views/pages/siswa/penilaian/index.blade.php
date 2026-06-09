@extends('layouts.main')

@section('title', 'Nilai Saya')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Nilai Saya</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mapel</th>
                            <th>Kelas</th>
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
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penilaian->mapel->nama_mapel }}</td>
                                <td>{{ $penilaian->kelas->nama_kelas }}</td>
                                <td>{{ $penilaian->semester }}</td>
                                <td>{{ $penilaian->tahun }}</td>
                                <td>{{ $penilaian->nilai_akhir }}</td>
                                <td>{{ $penilaian->nilai_rata_rata }}</td>
                                <td>
                                    <a href="{{ route('siswa.penilaian.show', $penilaian) }}" class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('penilaian.export.student.pdf', ['siswa_id' => $penilaian->siswa_id, 'kelas_id' => $penilaian->kelas_id, 'semester' => $penilaian->semester, 'tahun' => $penilaian->tahun]) }}" target="_blank" class="btn btn-sm btn-success">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data penilaian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
