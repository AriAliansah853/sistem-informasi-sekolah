@extends('layouts.main')
@section('title', 'Input Absensi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Input Absensi - {{ $kelas->nama_kelas }}</h1>
    </div>

    <div class="section-body">
        @include('partials.alert')

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('absensi.input', $kelas->id) }}">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $tanggal }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary">Tampilkan</button>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('absensi.store') }}">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
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
                                @forelse ($siswas as $siswa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->nis }}</td>
                                    <td>
                                        <input type="hidden" name="siswa_id[]" value="{{ $siswa->id }}">
                                        <select name="status[{{ $siswa->id }}]" class="form-control @error('status.' . $siswa->id) is-invalid @enderror">
                                            @php
                                                $selectedStatus = optional($absensis->get($siswa->id))->status;
                                            @endphp
                                            <option value="hadir" {{ $selectedStatus === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="izin" {{ $selectedStatus === 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="sakit" {{ $selectedStatus === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="alpha" {{ $selectedStatus === 'alpha' ? 'selected' : '' }}>Alpha</option>
                                        </select>
                                        @error('status.' . $siswa->id)
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </td>
                                    <td>
                                        <textarea class="form-control" name="keterangan[{{ $siswa->id }}]" rows="1">{{ optional($absensis->get($siswa->id))->keterangan }}</textarea>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada siswa di kelas ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                    <a href="{{ route('absensi.index') }}" class="btn btn-light">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
