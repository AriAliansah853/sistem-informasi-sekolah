@extends('layouts.front')
@section('title', 'Terima Kasih PPDB')

@section('content')
<section class="py-5 bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-body p-5 text-center">
            <span class="badge bg-success px-4 py-2">Terima Kasih</span>
            <h1 class="mt-4 fw-bold">Pendaftaran Anda Telah Diterima</h1>
            <p class="text-muted mb-4">Tim PPDB kami akan meninjau data pendaftaran dan menghubungi Anda melalui email atau telepon.</p>
            <a href="{{ route('landing') }}" class="btn btn-primary btn-lg">Kembali ke Halaman Utama</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
