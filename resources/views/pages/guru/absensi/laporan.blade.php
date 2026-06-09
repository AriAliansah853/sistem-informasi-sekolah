@extends('layouts.main')
@section('title', 'Laporan Absensi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Absensi</h1>
    </div>

    <div class="section-body">
        @include('partials.alert')

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('absensi.laporan') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kelas_id">Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="form-control">
                                    @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}" {{ optional($selectedKelas)->id == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="">-- Pilih Bulan --</option>
                                    @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ request('bulan') == $month ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select name="semester" id="semester" class="form-control">
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>Semester 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                            <a href="{{ route('absensi.index') }}" class="btn btn-light mt-2">Kembali</a>
                        </div>
                    </div>
                </form>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <h5>Ringkasan {{ optional($selectedKelas)->nama_kelas ?? 'Kelas belum dipilih' }} - {{ $periodLabel ?? '-' }}</h5>
                    <div>
                        <a href="{{ route('absensi.export.excel', request()->query()) }}" class="btn btn-success btn-sm">Export Excel</a>
                        <a href="{{ route('absensi.export.pdf', request()->query()) }}" class="btn btn-danger btn-sm">Export PDF</a>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Hadir</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $totals['hadir'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Izin</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $totals['izin'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="fas fa-procedures"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Sakit</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $totals['sakit'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Alpha</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $totals['alpha'] ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-striped">
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
                                <td colspan="5" class="text-center">Belum ada data absensi untuk tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
