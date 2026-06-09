@extends('layouts.main')

@section('title', 'Detail Nilai')

@section('content')
<div class="section">
    <div class="section-header">
        <h1>Detail Nilai</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <h5>Informasi Siswa</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Nama:</strong> {{ $penilaian->siswa->nama }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Kelas:</strong> {{ $penilaian->kelas->nama_kelas }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Mapel:</strong> {{ $penilaian->mapel->nama_mapel }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-2"><strong>Semester</strong><p>{{ $penilaian->semester }}</p></div>
                    <div class="col-md-2"><strong>Tahun</strong><p>{{ $penilaian->tahun }}</p></div>
                    <div class="col-md-2"><strong>Nilai Akhir</strong><p>{{ $penilaian->nilai_akhir }}</p></div>
                    <div class="col-md-2"><strong>Rata-rata</strong><p>{{ $penilaian->nilai_rata_rata }}</p></div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Komponen</th>
                            <th>Nilai</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Harian</td><td>{{ $penilaian->nilai_harian }}</td><td>{{ $penilaian->bobot_harian }}%</td></tr>
                        <tr><td>Tugas</td><td>{{ $penilaian->nilai_tugas }}</td><td>{{ $penilaian->bobot_tugas }}%</td></tr>
                        <tr><td>UTS</td><td>{{ $penilaian->nilai_uts }}</td><td>{{ $penilaian->bobot_uts }}%</td></tr>
                        <tr><td>UAS</td><td>{{ $penilaian->nilai_uas }}</td><td>{{ $penilaian->bobot_uas }}%</td></tr>
                        <tr><td>Sikap</td><td>{{ $penilaian->nilai_sikap }}</td><td>{{ $penilaian->bobot_sikap }}%</td></tr>
                        <tr><td>Kehadiran</td><td>{{ $penilaian->nilai_kehadiran }}</td><td>{{ $penilaian->bobot_kehadiran }}%</td></tr>
                    </tbody>
                </table>

                <div class="text-end">
                    <a href="{{ route('siswa.penilaian.index') }}" class="btn btn-secondary">Kembali</a>
                    <a href="{{ route('penilaian.export.student.pdf', ['siswa_id' => $penilaian->siswa_id, 'kelas_id' => $penilaian->kelas_id, 'semester' => $penilaian->semester, 'tahun' => $penilaian->tahun]) }}" target="_blank" class="btn btn-success">Cetak Rapor</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
