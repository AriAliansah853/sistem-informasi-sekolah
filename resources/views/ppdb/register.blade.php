@extends('layouts.front')
@section('title', 'Daftar PPDB')

@section('content')
<section class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-sm border-0">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <span class="badge bg-primary px-4 py-2">PPDB Online</span>
              <h1 class="mt-3 fw-bold">Pendaftaran Calon Siswa Baru</h1>
              <p class="text-muted">Isi formulir berikut untuk mendaftarkan diri sebagai calon siswa. Data akan diterima langsung oleh tim admin sekolah.</p>
            </div>

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('ppdb.register.store') }}" method="POST">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" required>
                  @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">NISN</label>
                  <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn') }}">
                  @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tempat Lahir</label>
                  <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}">
                  @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tanggal Lahir</label>
                  <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}">
                  @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">No. Telepon</label>
                  <input type="text" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror" value="{{ old('no_telp') }}" required>
                  @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                  @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Sekolah Asal</label>
                  <input type="text" name="sekolah_asal" class="form-control @error('sekolah_asal') is-invalid @enderror" value="{{ old('sekolah_asal') }}">
                  @error('sekolah_asal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                  <label class="form-label">Alamat Lengkap</label>
                  <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
                  @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Jurusan Pilihan</label>
                  <input type="text" name="jurusan_pilihan" class="form-control @error('jurusan_pilihan') is-invalid @enderror" value="{{ old('jurusan_pilihan') }}">
                  @error('jurusan_pilihan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Nama Orang Tua / Wali</label>
                  <input type="text" name="nama_ortu" class="form-control @error('nama_ortu') is-invalid @enderror" value="{{ old('nama_ortu') }}">
                  @error('nama_ortu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Telepon Orang Tua / Wali</label>
                  <input type="text" name="telp_ortu" class="form-control @error('telp_ortu') is-invalid @enderror" value="{{ old('telp_ortu') }}">
                  @error('telp_ortu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                  <label class="form-label">Catatan Tambahan</label>
                  <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                  @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary btn-lg">Kirim Pendaftaran</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
