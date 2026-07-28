@extends('layouts.main')
@section('title', 'Edit Program Unggulan')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Edit Program Unggulan</h4>
                            <a href="{{ route('program-unggulan.index') }}" class="btn btn-primary">Kembali</a>
                        </div>
                        <div class="card-body">
                            @include('partials.alert')
                            <form method="POST" action="{{ route('program-unggulan.update', $program) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Nama Program</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $program->name) }}">
                                </div>
                                <div class="form-group">
                                    <label for="target">Target</label>
                                    <input type="text" id="target" name="target" class="form-control @error('target') is-invalid @enderror" value="{{ old('target', $program->target) }}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $program->description) }}</textarea>
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
