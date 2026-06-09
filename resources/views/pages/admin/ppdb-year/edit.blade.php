@extends('layouts.main')
@section('title', 'Edit Data PPDB')

@section('content')
    <section class="section custom-section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Edit Data PPDB</h4>
                            <a href="{{ route('ppdb-year.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                        <div class="card-body">
                            @include('partials.alert')
                            <form action="{{ route('ppdb-year.update', $ppdbYear->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="year">Tahun</label>
                                            <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $ppdbYear->year) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="source_url">Sumber Data (URL)</label>
                                            <input type="url" name="source_url" id="source_url" class="form-control @error('source_url') is-invalid @enderror" value="{{ old('source_url', $ppdbYear->source_url) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_applicants">Jumlah Pendaftar</label>
                                            <input type="number" name="total_applicants" id="total_applicants" class="form-control" value="{{ old('total_applicants', $ppdbYear->total_applicants) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="accepted_count">Jumlah Diterima</label>
                                            <input type="number" name="accepted_count" id="accepted_count" class="form-control" value="{{ old('accepted_count', $ppdbYear->accepted_count) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="enrolled_count">Jumlah Terdaftar</label>
                                            <input type="number" name="enrolled_count" id="enrolled_count" class="form-control" value="{{ old('enrolled_count', $ppdbYear->enrolled_count) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_students">Jumlah Calon Baru</label>
                                            <input type="number" name="new_students" id="new_students" class="form-control" value="{{ old('new_students', $ppdbYear->new_students) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="summary">Ringkasan</label>
                                    <textarea name="summary" id="summary" rows="4" class="form-control">{{ old('summary', $ppdbYear->summary) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Data JSON</label>
                                    <textarea class="form-control" rows="4" disabled>{{ json_encode($ppdbYear->data_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</textarea>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Perbarui</button>
                                    <a href="{{ route('ppdb-year.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
