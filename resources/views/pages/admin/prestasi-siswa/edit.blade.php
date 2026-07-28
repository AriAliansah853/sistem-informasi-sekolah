@extends('layouts.main')
@section('title', 'Edit Prestasi Siswa')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Edit Prestasi Siswa</h4>
                            <a href="{{ route('prestasi-siswa.index') }}" class="btn btn-primary">Kembali</a>
                        </div>
                        <div class="card-body">
                            @include('partials.alert')
                            <form method="POST" action="{{ route('prestasi-siswa.update', $prestasi) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="nama_siswa">Nama Siswa</label>
                                    <input type="text" id="nama_siswa" name="nama_siswa" class="form-control @error('nama_siswa') is-invalid @enderror" value="{{ old('nama_siswa', $prestasi->nama_siswa) }}">
                                </div>
                                <div class="form-group">
                                    <label for="kelas">Kelas</label>
                                    <input type="text" id="kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas', $prestasi->kelas) }}">
                                </div>
                                <div class="form-group">
                                    <label for="jenis_prestasi">Jenis Prestasi</label>
                                    <input type="text" id="jenis_prestasi" name="jenis_prestasi" class="form-control @error('jenis_prestasi') is-invalid @enderror" value="{{ old('jenis_prestasi', $prestasi->jenis_prestasi) }}">
                                </div>
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="number" id="tahun" name="tahun" min="2000" max="2100" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $prestasi->tahun) }}">
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp; Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
