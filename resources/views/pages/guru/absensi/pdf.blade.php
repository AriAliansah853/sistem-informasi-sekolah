<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi {{ optional($selectedKelas)->nama_kelas }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { margin-bottom: 20px; }
        .header h2, .header p { margin: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Absensi</h2>
        <p>Kelas: {{ optional($selectedKelas)->nama_kelas ?? 'N/A' }}</p>
        <p>Periode: {{ $periodLabel }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensi as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->siswa->nama }}</td>
                <td>{{ $data->siswa->nis }}</td>
                <td>{{ ucfirst($data->status) }}</td>
                <td>{{ $data->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Tidak ada data absensi untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
