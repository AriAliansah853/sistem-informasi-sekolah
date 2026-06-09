@extends('layouts.main')
@section('title', 'Absensi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Absensi</h1>
    </div>

    <div class="section-body">
        @include('partials.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Pilih Kelas untuk Absensi</h4>
                        <a href="{{ route('absensi.laporan') }}" class="btn btn-info">Laporan Absensi</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kelas as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_kelas }}</td>
                                        <td>{{ $item->siswa->count() }}</td>
                                        <td>
                                            <a href="{{ route('absensi.input', $item->id) }}" class="btn btn-primary btn-sm">Input Absensi</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada kelas yang tersedia.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
