@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-book"></i> Bank Soal
            </h1>
            <small class="text-muted">Kelola soal-soal untuk ujian</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('bank-soal.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Soal Baru
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="mapel_id" class="form-label">Mata Pelajaran</label>
                    <select name="mapel_id" id="mapel_id" class="form-select">
                        <option value="">-- Semua Mata Pelajaran --</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" {{ request('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Soal -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50%">Judul Soal</th>
                        <th style="width: 15%">Tipe</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($soals as $soal)
                        <tr>
                            <td>
                                <strong>{{ $soal->judul }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $soal->mapel->nama ?? '-' }} • 
                                    Kesulitan: {{ $soal->tingkat_kesulitan }}/5
                                </small>
                            </td>
                            <td>
                                @php
                                    $tipeBadge = [
                                        'pilihan_ganda' => 'info',
                                        'essay' => 'warning',
                                        'benar_salah' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $tipeBadge[$soal->tipe] ?? 'dark' }}">
                                    {{ ucwords(str_replace('_', ' ', $soal->tipe)) }}
                                </span>
                            </td>
                            <td>
                                @if($soal->status === 'published')
                                    <span class="badge bg-success">Dipublikasikan</span>
                                @else
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('bank-soal.show', $soal->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('bank-soal.edit', $soal->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('bank-soal.destroy', $soal->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> Tidak ada soal
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $soals->links() }}
    </div>
</div>
@endsection
