@extends('layouts.main')

@section('content')
<div class="container-fluid py-2">
    <!-- Header dengan Timer -->
    <div class="row mb-3">
        <div class="col">
            <h4 class="mb-0">{{ $tryOut->judul }}</h4>
            <small class="text-muted">{{ $tryOut->mapel->nama }}</small>
        </div>
        <div class="col-auto">
            <div class="card border-warning">
                <div class="card-body py-2 px-3">
                    <div class="text-center">
                        <small class="text-muted d-block">Sisa Waktu</small>
                        <h4 class="mb-0" id="timer" style="font-weight: bold; color: #dc3545;">
                            {{ str_pad($tryOut->durasi_menit, 2, '0', STR_PAD_LEFT) }}:00
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Daftar Soal (Sidebar) -->
        <div class="col-lg-3">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list"></i> Soal ({{ count($soalFormatted) }})
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div style="max-height: 70vh; overflow-y: auto;">
                        @foreach($soalFormatted as $index => $soal)
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100 text-start py-2 border-0 rounded-0 soal-btn" 
                                    data-soal="{{ $index }}" onclick="showSoal({{ $index }})">
                                <span class="soal-number">{{ $index + 1 }}</span>
                                <span class="soal-status ms-2">
                                    <i class="fas fa-circle text-secondary"></i>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-check text-success"></i> Dijawab: <strong id="dijawab-count">0</strong>
                    </small>
                </div>
            </div>
        </div>

        <!-- Area Soal -->
        <div class="col-lg-9">
            <div id="soal-container">
                <!-- Soal akan ditampilkan di sini -->
            </div>

            <!-- Tombol Navigasi -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevSoal()">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="nextSoal()">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#selesaiModal">
                                <i class="fas fa-flag-checkered"></i> Selesaikan Ujian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Selesai -->
<div class="modal fade" id="selesaiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selesaikan Ujian?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menyelesaikan ujian ini?</p>
                <p class="text-muted small">
                    <strong>Catatan:</strong> Ujian tidak bisa dilanjutkan setelah diselesaikan.
                </p>
                <div class="alert alert-info">
                    <strong>Status:</strong>
                    <div>Soal dijawab: <strong id="modal-dijawab">0</strong>/{{ count($soalFormatted) }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="finalizeTryOut()">Ya, Selesaikan Ujian</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js"></script>
<script>
const tryOutId = {{ $tryOut->id }};
const durationMinutes = {{ $tryOut->durasi_menit }};
let currentSoal = 0;
let soalData = @json($soalFormatted);
let jawabanSiswa = @json($savedAnswers ?? []);
let timerInterval;
let autoSaveInterval;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
if (csrfTokenElement) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfTokenElement.content;
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    initializeSoal();
    startTimer();
    startAutoSave();
});

function initializeSoal() {
    showSoal(0);
}

function showSoal(index) {
    if (index < 0 || index >= soalData.length) return;
    
    currentSoal = index;
    const soal = soalData[index];
    
    let html = `
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    Soal ${index + 1} dari {{ count($soalFormatted) }}
                    @if($tryOut->acak_soal)
                        <span class="badge bg-info ms-2">Acak</span>
                    @endif
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6><strong>${soal.judul || 'Soal ' + (index + 1)}</strong></h6>
                    <div class="bg-light p-3 rounded mb-3">
                        ${soal.isi}
                    </div>
                </div>
    `;

    if (soal.tipe === 'pilihan_ganda') {
        html += renderPilihanGanda(soal, index);
    } else if (soal.tipe === 'benar_salah') {
        html += renderBenarSalah(soal, index);
    } else if (soal.tipe === 'essay') {
        html += renderEssay(soal, index);
    }

    html += `
            </div>
        </div>
    `;

    document.getElementById('soal-container').innerHTML = html;
    updateSoalList();
}

function renderPilihanGanda(soal, index) {
    let html = '<div class="mb-3">';
    
    soal.opsi_soal.forEach((opsi, idx) => {
        const isSelected = jawabanSiswa[index]?.opsi_soal_id == opsi.id;
        html += `
            <div class="form-check mb-2">
                <input type="radio" name="jawaban_${index}" id="opsi_${index}_${idx}" 
                       class="form-check-input" value="${opsi.id}" 
                       onchange="saveJawaban(${index}, 'opsi', ${opsi.id})"
                       ${isSelected ? 'checked' : ''}>
                <label class="form-check-label" for="opsi_${index}_${idx}">
                    <strong>${opsi.kode}.</strong> ${opsi.teks}
                </label>
            </div>
        `;
    });
    
    html += '</div>';
    return html;
}

function renderBenarSalah(soal, index) {
    let html = '<div class="mb-3">';
    
    soal.opsi_soal.forEach((opsi, idx) => {
        const isSelected = jawabanSiswa[index]?.jawaban === opsi.kode;
        html += `
            <div class="form-check mb-2">
                <input type="radio" name="jawaban_${index}" id="opsi_${index}_${idx}" 
                       class="form-check-input" value="${opsi.kode}" 
                       onchange="saveJawaban(${index}, 'text', '${opsi.kode}')"
                       ${isSelected ? 'checked' : ''}>
                <label class="form-check-label" for="opsi_${index}_${idx}">
                    ${opsi.teks}
                </label>
            </div>
        `;
    });
    
    html += '</div>';
    return html;
}

function renderEssay(soal, index) {
    const jawaban = jawabanSiswa[index]?.jawaban || '';
    return `
        <div class="mb-3">
            <textarea id="essay_${index}" class="form-control" rows="6" 
                      placeholder="Tulis jawaban Anda di sini..."
                      onchange="saveJawaban(${index}, 'essay', this.value)">${jawaban}</textarea>
        </div>
    `;
}

function saveJawaban(soalIndex, type, value) {
    if (!jawabanSiswa[soalIndex]) {
        jawabanSiswa[soalIndex] = {};
    }

    if (type === 'opsi') {
        jawabanSiswa[soalIndex].opsi_soal_id = value;
    } else if (type === 'text') {
        jawabanSiswa[soalIndex].jawaban = value;
    } else if (type === 'essay') {
        jawabanSiswa[soalIndex].jawaban = value;
    }

    updateSoalList();
}

function updateSoalList() {
    // Update status di sidebar
    document.querySelectorAll('.soal-btn').forEach((btn, idx) => {
        const soalStatus = btn.querySelector('.soal-status i');
        if (jawabanSiswa[idx]) {
            soalStatus.className = 'fas fa-circle text-success';
        } else {
            soalStatus.className = 'fas fa-circle text-secondary';
        }
    });

    // Update counter
    const dijawab = Object.keys(jawabanSiswa).length;
    document.getElementById('dijawab-count').textContent = dijawab;
    document.getElementById('modal-dijawab').textContent = dijawab;

    // Highlight current soal
    document.querySelectorAll('.soal-btn').forEach((btn, idx) => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-secondary');
    });
    document.querySelector(`.soal-btn[data-soal="${currentSoal}"]`).classList.add('btn-primary');
}

function nextSoal() {
    if (currentSoal < soalData.length - 1) {
        showSoal(currentSoal + 1);
    }
}

function prevSoal() {
    if (currentSoal > 0) {
        showSoal(currentSoal - 1);
    }
}

function startTimer() {
    let timeRemaining = durationMinutes * 60;
    const timerDisplay = document.getElementById('timer');

    timerInterval = setInterval(() => {
        timeRemaining--;

        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        timerDisplay.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

        // Change color saat 5 menit terakhir
        if (timeRemaining <= 300) {
            timerDisplay.style.color = '#dc3545';
        }

        // Auto submit saat waktu habis
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            clearInterval(autoSaveInterval);
            finalizeTryOut();
        }
    }, 1000);
}

function startAutoSave() {
    // Auto-save setiap 10 detik
    autoSaveInterval = setInterval(() => {
        autoSaveAllAnswers();
    }, 10000);
}

function saveJawabanToServer(soalIndex) {
    if (!jawabanSiswa[soalIndex]) return Promise.resolve();

    const soal = soalData[soalIndex];
    const jawaban = jawabanSiswa[soalIndex];

    let data = {
        try_out_id: tryOutId,
        try_out_soal_id: soal.try_out_soal_id,
        bank_soal_id: soal.bank_soal_id,
        waktu_dikerjakan_detik: 0
    };

    if (jawaban.opsi_soal_id) {
        data.opsi_soal_id = jawaban.opsi_soal_id;
    }
    if (jawaban.jawaban) {
        data.jawaban = jawaban.jawaban;
    }

    return axios.post('{{ route("try-out-jawaban.save") }}', data)
        .catch(err => {
            console.error('Error saving answer:', err);
        });
}

function autoSaveAllAnswers() {
    const promises = Object.keys(jawabanSiswa).map(idx => saveJawabanToServer(idx));
    return Promise.all(promises);
}

function finalizeTryOut() {
    // Simpan semua jawaban terakhir
    autoSaveAllAnswers().finally(() => {
        clearInterval(timerInterval);
        clearInterval(autoSaveInterval);

        // Send finalize request
        axios.post('{{ route("try-out-jawaban.finalize") }}', {
            try_out_id: tryOutId
        })
        .then(response => {
            alert('Ujian selesai! Skor Anda: ' + response.data.skor_akhir);
            window.location.href = '{{ route("try-out-jawaban.index-siswa") }}';
        })
        .catch(err => {
            alert('Terjadi kesalahan: ' + (err.response?.data?.message || 'Error'));
        });
    });
}
</script>

<style>
.soal-btn {
    border: 1px solid #dee2e6;
    border-bottom: none;
}

.soal-btn:last-child {
    border-bottom: 1px solid #dee2e6;
}

.soal-btn:hover {
    background-color: #f8f9fa;
}

.soal-btn.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endsection
