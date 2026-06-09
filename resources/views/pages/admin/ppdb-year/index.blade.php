@extends('layouts.main')
@section('title', 'PPDB Tahunan')

@section('content')
    <section class="section custom-section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Data PPDB Tahunan</h4>
                            <div>
                                <button class="btn btn-outline-primary me-2" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-import"></i> Impor dari Web</button>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Tambah Data</button>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('partials.alert')
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-2">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tahun</th>
                                            <th>Pendaftar</th>
                                            <th>Diterima</th>
                                            <th>Terdaftar</th>
                                            <th>Calon Baru</th>
                                            <th>Sumber</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ppdbYears as $ppdb)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $ppdb->year }}</td>
                                                <td>{{ $ppdb->total_applicants ?? '-' }}</td>
                                                <td>{{ $ppdb->accepted_count ?? '-' }}</td>
                                                <td>{{ $ppdb->enrolled_count ?? '-' }}</td>
                                                <td>{{ $ppdb->new_students ?? '-' }}</td>
                                                <td>{{ $ppdb->source_url ? (strlen($ppdb->source_url) > 40 ? substr($ppdb->source_url, 0, 40) . '...' : $ppdb->source_url) : '-' }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('ppdb-year.edit', $ppdb->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                                        <form method="POST" action="{{ route('ppdb-year.destroy', $ppdb->id) }}">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-danger btn-sm show_confirm" data-toggle="tooltip" title="Hapus" style="margin-left: 8px"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="createModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Data PPDB</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('ppdb-year.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="year">Tahun</label>
                                        <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}" placeholder="2026">
                                    </div>
                                    <div class="form-group">
                                        <label for="total_applicants">Jumlah Pendaftar</label>
                                        <input type="number" name="total_applicants" id="total_applicants" class="form-control" value="{{ old('total_applicants') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="accepted_count">Jumlah Diterima</label>
                                        <input type="number" name="accepted_count" id="accepted_count" class="form-control" value="{{ old('accepted_count') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="enrolled_count">Jumlah Terdaftar</label>
                                        <input type="number" name="enrolled_count" id="enrolled_count" class="form-control" value="{{ old('enrolled_count') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="new_students">Jumlah Calon Baru</label>
                                        <input type="number" name="new_students" id="new_students" class="form-control" value="{{ old('new_students') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="source_url">Sumber Data (URL)</label>
                                        <input type="url" name="source_url" id="source_url" class="form-control" value="{{ old('source_url') }}" placeholder="https://example.com/api/ppdb">
                                    </div>
                                    <div class="form-group">
                                        <label for="summary">Ringkasan</label>
                                        <textarea name="summary" id="summary" rows="3" class="form-control">{{ old('summary') }}</textarea>
                                    </div>
                                    <div class="modal-footer br">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="importModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Impor Data PPDB dari Web</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('ppdb-year.import') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="source_url_import">URL Sumber Data</label>
                                        <input type="url" name="source_url" id="source_url_import" class="form-control @error('source_url') is-invalid @enderror" value="{{ old('source_url') }}" placeholder="https://example.com/api/ppdb">
                                    </div>
                                    <div class="modal-footer br">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Impor</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest('form');
            event.preventDefault();
            swal({
                    title: `Yakin ingin menghapus data ini?`,
                    text: 'Data akan terhapus secara permanen!',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>
@endpush
