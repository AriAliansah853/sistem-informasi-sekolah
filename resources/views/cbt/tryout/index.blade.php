@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-list-check"></i> Daftar Try Out
            </h1>
            <small class="text-muted">Kelola ujian online Anda</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('try-out.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Try Out Baru
            </a>
        </div>
    </div>

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
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Try Out -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Waktu Mulai</th>
                        <th>Jumlah Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tryOuts as $tryOut)
                        <tr>
                            <td>
                                <strong>{{ $tryOut->judul }}</strong>
                                <br>
                                <small class="text-muted">Durasi: {{ $tryOut->durasi_menit }} menit • Soal: {{ $tryOut->jumlah_soal }}</small>
                            </td>
                            <td>{{ $tryOut->mapel->nama ?? '-' }}</td>
                            <td>
                                @if($tryOut->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($tryOut->status === 'draft')
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @else
                                    <span class="badge bg-secondary">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $tryOut->waktu_mulai->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $tryOut->statistik->jumlah_peserta ?? 0 }}</span>
                            </td>
                            <td>
                                <a href="{{ route('try-out.show', $tryOut->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('try-out.edit', $tryOut->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($tryOut->status === 'draft')
                                    <a href="{{ route('try-out.edit-soal', $tryOut->id) }}" class="btn btn-sm btn-primary" title="Atur Soal">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('try-out.destroy', $tryOut->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> Tidak ada try out
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $tryOuts->links() }}
    </div>
</div>
@endsection
