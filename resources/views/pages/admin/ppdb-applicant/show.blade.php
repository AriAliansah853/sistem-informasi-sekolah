@extends('layouts.main')
@section('title', 'Detail Pendaftaran PPDB')

@section('content')
<section class="section">
  <div class="section-header d-flex justify-content-between align-items-center">
    <h1>Detail Calon Siswa PPDB</h1>
    <div>
      <a href="{{ route('ppdb-applicant.index') }}" class="btn btn-secondary">Kembali</a>
      <form action="{{ route('ppdb-applicant.destroy', $applicant->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Hapus pendaftaran ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Hapus</button>
      </form>
    </div>
  </div>

  <div class="section-body">
    @include('partials.alert')
    <div class="row">
      <div class="col-lg-7">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-4">Informasi Calon Siswa</h5>
            <dl class="row">
              <dt class="col-sm-4">Nama Lengkap</dt>
              <dd class="col-sm-8">{{ $applicant->nama_lengkap }}</dd>

              <dt class="col-sm-4">NISN</dt>
              <dd class="col-sm-8">{{ $applicant->nisn ?? '-' }}</dd>

              <dt class="col-sm-4">Tempat, Tanggal Lahir</dt>
              <dd class="col-sm-8">{{ $applicant->tempat_lahir ?? '-' }}, {{ optional($applicant->tanggal_lahir)->format('d M Y') ?? '-' }}</dd>

              <dt class="col-sm-4">Jenis Kelamin</dt>
              <dd class="col-sm-8">{{ $applicant->jenis_kelamin ?? '-' }}</dd>

              <dt class="col-sm-4">Alamat</dt>
              <dd class="col-sm-8">{{ $applicant->alamat ?? '-' }}</dd>

              <dt class="col-sm-4">No. Telepon</dt>
              <dd class="col-sm-8">{{ $applicant->no_telp ?? '-' }}</dd>

              <dt class="col-sm-4">Email</dt>
              <dd class="col-sm-8">{{ $applicant->email ?? '-' }}</dd>

              <dt class="col-sm-4">Sekolah Asal</dt>
              <dd class="col-sm-8">{{ $applicant->sekolah_asal ?? '-' }}</dd>

              <dt class="col-sm-4">Jurusan Pilihan</dt>
              <dd class="col-sm-8">{{ $applicant->jurusan_pilihan ?? '-' }}</dd>

              <dt class="col-sm-4">Nama Orang Tua/Wali</dt>
              <dd class="col-sm-8">{{ $applicant->nama_ortu ?? '-' }}</dd>

              <dt class="col-sm-4">Telepon Orang Tua/Wali</dt>
              <dd class="col-sm-8">{{ $applicant->telp_ortu ?? '-' }}</dd>

              <dt class="col-sm-4">Catatan Tambahan</dt>
              <dd class="col-sm-8">{{ $applicant->keterangan ?? '-' }}</dd>

              <dt class="col-sm-4">Status</dt>
              <dd class="col-sm-8">
                <span class="badge bg-{{ $applicant->status == 'diterima' ? 'success' : ($applicant->status == 'ditolak' ? 'danger' : 'warning') }} text-dark">
                  {{ ucfirst($applicant->status) }}
                </span>
              </dd>
            </dl>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-4">Update Status Pendaftaran</h5>
            <form action="{{ route('ppdb-applicant.update', $applicant->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                  <option value="pending" {{ $applicant->status == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="diterima" {{ $applicant->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                  <option value="ditolak" {{ $applicant->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Catatan Admin</label>
                <textarea name="admin_note" rows="4" class="form-control @error('admin_note') is-invalid @enderror">{{ old('admin_note', $applicant->admin_note) }}</textarea>
                @error('admin_note')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
