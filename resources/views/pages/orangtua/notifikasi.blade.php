@extends('layouts.main')
@section('title', 'Notifikasi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Notifikasi Orangtua</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Notifikasi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu</th>
                                <th>Judul</th>
                                <th>Pesan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $notification->title }}</td>
                                <td>{{ $notification->message }}</td>
                                <td>
                                    @if ($notification->is_read)
                                        <span class="badge badge-success">Dibaca</span>
                                    @else
                                        <span class="badge badge-warning">Baru</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada notifikasi.</td>
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
