<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rapor {{ $siswa->nama }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #2a2a2a; margin: 0; padding: 0; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-height: 80px; margin-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #555; margin-bottom: 15px; }
        .details, .scores { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details td, .scores th, .scores td { border: 1px solid #ccc; padding: 8px; }
        .scores th { background: #f5f5f5; }
        .text-right { text-align: right; }
        .signature { width: 100%; margin-top: 40px; }
        .signature td { vertical-align: top; padding: 8px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        @if($pengaturan && $pengaturan->logo)
            <img src="{{ public_path($pengaturan->logo) }}" alt="Logo Sekolah">
        @endif
        <div class="title">{{ $pengaturan->name ?? 'Sekolah' }}</div>
        <div class="subtitle">Laporan Nilai Siswa - Semester {{ $request->semester }} Tahun {{ $request->tahun }}</div>
    </div>

    <table class="details">
        <tr>
            <td><strong>Nama Siswa</strong></td>
            <td>{{ $siswa->nama }}</td>
            <td><strong>NIS</strong></td>
            <td>{{ $siswa->nis }}</td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>{{ optional($penilaians->first())->kelas->nama_kelas }}</td>
            <td><strong>Semester</strong></td>
            <td>{{ $request->semester }}</td>
        </tr>
        <tr>
            <td><strong>Tahun</strong></td>
            <td>{{ $request->tahun }}</td>
            <td><strong>Ranking Semester</strong></td>
            <td>{{ $rank }}</td>
        </tr>
    </table>

    <table class="scores">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Harian</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Sikap</th>
                <th>Kehadiran</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penilaians as $penilaian)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $penilaian->mapel->nama_mapel }}</td>
                    <td>{{ $penilaian->nilai_harian }}</td>
                    <td>{{ $penilaian->nilai_tugas }}</td>
                    <td>{{ $penilaian->nilai_uts }}</td>
                    <td>{{ $penilaian->nilai_uas }}</td>
                    <td>{{ $penilaian->nilai_sikap }}</td>
                    <td>{{ $penilaian->nilai_kehadiran }}</td>
                    <td>{{ $penilaian->nilai_akhir }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="8" class="text-right"><strong>Rata-rata Nilai</strong></td>
                <td>{{ $averageScore }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td style="width: 50%;">
                <strong>Mengetahui,</strong><br>
                Kepala Sekolah<br><br><br><br>
                __________________________
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Guru Pengampu</strong><br>
                {{ optional($penilaians->first())->guru->nama ?? '-' }}<br><br><br><br>
                __________________________
            </td>
        </tr>
    </table>
</div>
</body>
</html>
