@extends('layouts.main')
@section('title', 'Daftar Calon Siswa PPDB')

@section('content')
<section class="section">
  <div class="section-header d-flex justify-content-between align-items-center">
    <h1>Calon Siswa PPDB</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
  </div>
  <div class="section-body">
    @include('partials.alert')
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>Jurusan Pilihan</th>
                <th>Sekolah Asal</th>
                <th>Status</th>
                <th>Diajukan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($applicants as $applicant)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $applicant->nama_lengkap }}</td>
                  <td>{{ $applicant->nisn ?? '-' }}</td>
                  <td>{{ $applicant->jurusan_pilihan ?? '-' }}</td>
                  <td>{{ $applicant->sekolah_asal ?? '-' }}</td>
                  <td>
                    <span class="badge bg-{{ $applicant->status == 'diterima' ? 'success' : ($applicant->status == 'ditolak' ? 'danger' : 'warning') }} text-dark">
                      {{ ucfirst($applicant->status) }}
                    </span>
                  </td>
                  <td>{{ $applicant->created_at->format('d M Y') }}</td>
                  <td>
                    <a href="{{ route('ppdb-applicant.show', $applicant->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <form action="{{ route('ppdb-applicant.destroy', $applicant->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Hapus pendaftaran ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center">Belum ada pendaftaran PPDB.</td>
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
