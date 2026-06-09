@extends('layouts.main')
@section('title', 'Pengaturan')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Pengaturan</h4>
                        </div>
                        <div class="card-body">
                            @include('partials.alert')
                            <form method="POST" action="{{ route('pengaturan.update', $pengaturan->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
<<<<<<< HEAD
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_sekolah">Nama Sekolah</label>
                                            <input type="text" id="nama_sekolah" name="nama_sekolah"
                                                class="form-control @error('nama_sekolah') is-invalid @enderror"
                                                placeholder="{{ __('Nama Sekolah') }}" value="{{ $pengaturan->name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo">Logo Sekolah</label>
                                            <div>
                                                <img src="{{ URL::asset($pengaturan->logo) ?? 'https://via.placeholder.com/300' }}"
                                                    alt="Logo Sekolah" width="100" class="mb-2">
                                            </div>
                                            <input type="file" id="logo" name="logo"
                                                class="form-control @error('logo') is-invalid @enderror">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Bagian Hero</h5>
                                        <div class="form-group">
                                            <label for="hero_title">Judul Hero</label>
                                            <input type="text" id="hero_title" name="hero_title"
                                                class="form-control @error('hero_title') is-invalid @enderror"
                                                value="{{ $pengaturan->hero_title }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="hero_subtitle">Subjudul Hero</label>
                                            <textarea id="hero_subtitle" name="hero_subtitle" rows="3"
                                                class="form-control @error('hero_subtitle') is-invalid @enderror">{{ $pengaturan->hero_subtitle }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="hero_cta_text">Teks Tombol CTA</label>
                                            <input type="text" id="hero_cta_text" name="hero_cta_text"
                                                class="form-control @error('hero_cta_text') is-invalid @enderror"
                                                value="{{ $pengaturan->hero_cta_text }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="hero_cta_link">Link Tombol CTA</label>
                                            <input type="text" id="hero_cta_link" name="hero_cta_link"
                                                class="form-control @error('hero_cta_link') is-invalid @enderror"
                                                value="{{ $pengaturan->hero_cta_link }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="hero_image">Gambar Hero</label>
                                            <div class="mb-2">
                                                <img src="{{ URL::asset($pengaturan->hero_image) ?? 'https://via.placeholder.com/600x300' }}"
                                                    alt="Hero Image" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                            <input type="file" id="hero_image" name="hero_image"
                                                class="form-control @error('hero_image') is-invalid @enderror">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-3">Konten Landing Page</h5>
                                        <div class="form-group">
                                            <label for="about_title">Judul Tentang</label>
                                            <input type="text" id="about_title" name="about_title"
                                                class="form-control @error('about_title') is-invalid @enderror"
                                                value="{{ $pengaturan->about_title }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="about_description">Deskripsi Tentang</label>
                                            <textarea id="about_description" name="about_description" rows="4"
                                                class="form-control @error('about_description') is-invalid @enderror">{{ $pengaturan->about_description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="visi">Visi</label>
                                            <textarea id="visi" name="visi" rows="3"
                                                class="form-control @error('visi') is-invalid @enderror">{{ $pengaturan->visi }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="misi">Misi</label>
                                            <textarea id="misi" name="misi" rows="3"
                                                class="form-control @error('misi') is-invalid @enderror">{{ $pengaturan->misi }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5 class="mb-3">Kontak Sekolah</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact_address">Alamat</label>
                                                    <textarea id="contact_address" name="contact_address" rows="2"
                                                        class="form-control @error('contact_address') is-invalid @enderror">{{ $pengaturan->contact_address }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contact_phone">Telepon</label>
                                                    <input type="text" id="contact_phone" name="contact_phone"
                                                        class="form-control @error('contact_phone') is-invalid @enderror"
                                                        value="{{ $pengaturan->contact_phone }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="contact_email">Email</label>
                                                    <input type="email" id="contact_email" name="contact_email"
                                                        class="form-control @error('contact_email') is-invalid @enderror"
                                                        value="{{ $pengaturan->contact_email }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer mt-4">
=======
                                <div class="form-group">
                                    <label for="nama_sekolah">Nama Sekolah</label>
                                    <input type="text" id="nama_sekolah" name="nama_sekolah"
                                        class="form-control @error('nama_sekolah') is-invalid @enderror"
                                        placeholder="{{ __('Nama Sekolah') }}" value="{{ $pengaturan->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo Sekolah</label>
                                    <div>
                                        <img src="{{ URL::asset($pengaturan->logo) ?? 'https://via.placeholder.com/300' }}"
                                            alt="Logo Sekolah" width="100" class="mb-2">
                                    </div>
                                    <input type="file" id="logo" name="logo"
                                        class="form-control @error('logo') is-invalid @enderror">
                                </div>
                                <div class="card-footer">
>>>>>>> a01621e (Initial commit)
                                    <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i>
                                        &nbsp; Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
