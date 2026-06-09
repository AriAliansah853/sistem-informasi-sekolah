@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-circle"></i> Buat Soal Baru
            </h1>
            <small class="text-muted">Tambahkan soal baru ke bank soal</small>
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

    <form action="{{ route('bank-soal.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Informasi Dasar</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="mapel_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="mapel_id" id="mapel_id" class="form-select @error('mapel_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tipe" class="form-label">Tipe Soal <span class="text-danger">*</span></label>
                        <select name="tipe" id="tipe" class="form-select @error('tipe') is-invalid @enderror" onchange="updateTipeDisplay()" required>
                            <option value="">-- Pilih Tipe Soal --</option>
                            <option value="pilihan_ganda" {{ old('tipe') == 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="essay" {{ old('tipe') == 'essay' ? 'selected' : '' }}>Essay</option>
                            <option value="benar_salah" {{ old('tipe') == 'benar_salah' ? 'selected' : '' }}>Benar/Salah</option>
                        </select>
                        @error('tipe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Soal <span class="text-danger">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" 
                           value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="isi" class="form-label">Isi Soal <span class="text-danger">*</span></label>
                    <textarea name="isi" id="isi" class="form-control @error('isi') is-invalid @enderror" 
                              rows="5" required>{{ old('isi') }}</textarea>
                    @error('isi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="tingkat_kesulitan" class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                        <select name="tingkat_kesulitan" id="tingkat_kesulitan" class="form-select @error('tingkat_kesulitan') is-invalid @enderror" required>
                            <option value="1" {{ old('tingkat_kesulitan') == 1 ? 'selected' : '' }}>1 - Sangat Mudah</option>
                            <option value="2" {{ old('tingkat_kesulitan') == 2 ? 'selected' : '' }}>2 - Mudah</option>
                            <option value="3" {{ old('tingkat_kesulitan') == 3 ? 'selected' : '' }}>3 - Sedang</option>
                            <option value="4" {{ old('tingkat_kesulitan') == 4 ? 'selected' : '' }}>4 - Sulit</option>
                            <option value="5" {{ old('tingkat_kesulitan') == 5 ? 'selected' : '' }}>5 - Sangat Sulit</option>
                        </select>
                        @error('tingkat_kesulitan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="bobot" class="form-label">Bobot Nilai <span class="text-danger">*</span></label>
                        <input type="number" name="bobot" id="bobot" class="form-control @error('bobot') is-invalid @enderror" 
                               value="{{ old('bobot', 1) }}" min="1" max="100" required>
                        @error('bobot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Opsi Soal untuk Pilihan Ganda & Benar Salah -->
        <div class="card mb-4" id="opsi-container" style="display:none;">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Pilihan Jawaban</h5>
            </div>
            <div class="card-body">
                <div id="opsi-list"></div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOpsi()">
                    <i class="fas fa-plus"></i> Tambah Pilihan
                </button>
            </div>
        </div>

        <!-- Pembahasan -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Pembahasan (Opsional)</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="pembahasan" class="form-label">Pembahasan</label>
                    <textarea name="pembahasan" id="pembahasan" class="form-control" rows="4">{{ old('pembahasan') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="kata_kunci" class="form-label">Kata Kunci</label>
                    <input type="text" name="kata_kunci" id="kata_kunci" class="form-control" 
                           value="{{ old('kata_kunci') }}" placeholder="Pisahkan dengan koma">
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Status</h5>
            </div>
            <div class="card-body">
                <div class="form-check">
                    <input type="radio" name="status" id="status_draft" value="draft" 
                           class="form-check-input" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_draft">
                        Draft (Tidak ditampilkan untuk pembuatan try out)
                    </label>
                </div>
                <div class="form-check">
                    <input type="radio" name="status" id="status_published" value="published" 
                           class="form-check-input" {{ old('status') == 'published' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_published">
                        Dipublikasikan (Bisa digunakan di try out)
                    </label>
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Soal
            </button>
            <a href="{{ route('bank-soal.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
const opsiKode = ['A', 'B', 'C', 'D', 'E'];
let opsiCount = 0;

function updateTipeDisplay() {
    const tipe = document.getElementById('tipe').value;
    const opsiContainer = document.getElementById('opsi-container');
    
    if (tipe === 'pilihan_ganda') {
        opsiContainer.style.display = 'block';
        if (document.getElementById('opsi-list').innerHTML === '') {
            for (let i = 0; i < 4; i++) addOpsi();
        }
    } else if (tipe === 'benar_salah') {
        opsiContainer.style.display = 'block';
        document.getElementById('opsi-list').innerHTML = '';
        addBenarSalahOpsi();
    } else {
        opsiContainer.style.display = 'none';
    }
}

function addOpsi() {
    const list = document.getElementById('opsi-list');
    const index = document.querySelectorAll('.opsi-item').length;
    
    if (index >= 5) {
        alert('Maksimal 5 pilihan');
        return;
    }

    const html = `
        <div class="opsi-item card mb-3 border-secondary" data-index="${index}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1">
                        <label class="form-label">Kode</label>
                        <input type="text" name="opsi_soal[${index}][kode]" class="form-control" 
                               value="${opsiKode[index]}" readonly>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Teks Pilihan</label>
                        <textarea name="opsi_soal[${index}][teks]" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <div class="form-check">
                                <input type="radio" name="opsi_benar" value="${index}" id="benar_${index}" class="form-check-input">
                                <input type="hidden" name="opsi_soal[${index}][is_correct]" value="0" id="correct_${index}">
                                <label class="form-check-label" for="benar_${index}">
                                    Jawaban Benar
                                </label>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeOpsi(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    list.insertAdjacentHTML('beforeend', html);
    
    // Add change listener untuk radio
    document.getElementById(`benar_${index}`).addEventListener('change', function() {
        document.querySelectorAll('input[type="hidden"][name*="is_correct"]').forEach(el => {
            el.value = '0';
        });
        if (this.checked) {
            document.getElementById(`correct_${index}`).value = '1';
        }
    });
}

function addBenarSalahOpsi() {
    const list = document.getElementById('opsi-list');
    const opsi = [
        { kode: 'B', teks: 'Benar' },
        { kode: 'S', teks: 'Salah' }
    ];

    opsi.forEach((o, index) => {
        const html = `
            <div class="opsi-item card mb-3 border-secondary" data-index="${index}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label">Kode</label>
                            <input type="text" name="opsi_soal[${index}][kode]" class="form-control" 
                                   value="${o.kode}" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Teks</label>
                            <input type="text" name="opsi_soal[${index}][teks]" class="form-control" 
                                   value="${o.teks}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check">
                                <input type="radio" name="opsi_benar" value="${index}" id="benar_${index}" class="form-check-input">
                                <input type="hidden" name="opsi_soal[${index}][is_correct]" value="0" id="correct_${index}">
                                <label class="form-check-label" for="benar_${index}">
                                    Benar
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        list.insertAdjacentHTML('beforeend', html);
        
        document.getElementById(`benar_${index}`).addEventListener('change', function() {
            document.querySelectorAll('input[type="hidden"][name*="is_correct"]').forEach(el => {
                el.value = '0';
            });
            if (this.checked) {
                document.getElementById(`correct_${index}`).value = '1';
            }
        });
    });
}

function removeOpsi(index) {
    document.querySelector(`.opsi-item[data-index="${index}"]`).remove();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateTipeDisplay);
</script>

<style>
.opsi-item {
    border-left: 4px solid #0d6efd;
}
</style>
@endsection
