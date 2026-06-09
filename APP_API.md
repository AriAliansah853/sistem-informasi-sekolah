# Dokumentasi API Internal

## Ringkasan
Aplikasi ini menggunakan Laravel Web Routes dengan pola resource untuk banyak entitas. Meskipun bukan API RESTful terpisah, rute internal ini adalah antarmuka utama untuk operasi CRUD dan interaksi role-based.

## 1. Auth & Profil
- `GET /` - Landing page publik
- `GET /home` - Halaman home setelah login
- `GET /profile` - Edit profil user
- `PUT /update-profile` - Update profil user
- `GET /edit-password` - Form ubah password
- `PATCH /update-password` - Proses ubah password

## 2. Role: Guru
### Materi
- `GET /materi` - Daftar materi guru
- `GET /materi/create` - Form tambah materi
- `POST /materi` - Simpan materi baru
- `GET /materi/{materi}` - Tampilkan detail materi
- `GET /materi/{materi}/edit` - Form edit materi
- `PUT/PATCH /materi/{materi}` - Perbarui materi
- `DELETE /materi/{materi}` - Hapus materi

### Tugas
- `GET /tugas` - Daftar tugas guru
- `GET /tugas/create` - Form tambah tugas
- `POST /tugas` - Simpan tugas baru
- `GET /tugas/{tugas}` - Detail tugas
- `GET /tugas/{tugas}/edit` - Edit tugas
- `PUT/PATCH /tugas/{tugas}` - Perbarui tugas
- `DELETE /tugas/{tugas}` - Hapus tugas
- `GET /jawaban-download/{id}` - Unduh jawaban siswa

### Absensi
- `GET /absensi` - Daftar absensi
- `GET /absensi/input/{kelas}` - Form input absensi per kelas
- `POST /absensi/store` - Simpan absensi
- `GET /absensi/laporan` - Laporan absensi
- `GET /absensi/export-excel` - Ekspor Excel
- `GET /absensi/export-pdf` - Ekspor PDF

### Penilaian
- `GET /penilaian` - Daftar penilaian
- `GET /penilaian/create` - Form tambah penilaian
- `POST /penilaian` - Simpan penilaian
- `GET /penilaian/{penilaian}/edit` - Edit penilaian
- `PUT/PATCH /penilaian/{penilaian}` - Update penilaian
- `GET /penilaian/rekap` - Rekap penilaian

### CBT Guru
- `GET /bank-soal` - Daftar bank soal
- `POST /bank-soal` - Tambah bank soal
- `PATCH /bank-soal/{bankSoal}/publish` - Publish bank soal
- `GET /try-out` - Daftar try-out
- `PATCH /try-out/{tryOut}/publish` - Publish try-out
- `GET /try-out/{tryOut}/edit-soal` - Edit susunan soal try-out
- `POST /try-out/{tryOut}/add-soal` - Tambah soal ke try-out
- `GET /try-out/available-soals` - Ambil soal yang tersedia

### Hasil Try Out
- `GET /hasil-try-out` - Daftar hasil try-out
- `GET /hasil-try-out/{hasilTryOut}` - Detail hasil try-out
- `GET /hasil-try-out/{hasilTryOut}/siswa` - Tampilkan hasil per siswa
- `GET /hasil-try-out/{tryOut}/export` - Ekspor hasil try-out
- `GET /hasil-try-out/{tryOut}/statistik-chart` - Ambil data grafik statistik

## 3. Role: Siswa
- `GET /siswa/dashboard` - Dashboard siswa
- `GET /siswa/materi` - Daftar materi siswa
- `GET /materi-download/{id}` - Download materi
- `GET /siswa/tugas` - Daftar tugas siswa
- `GET /tugas-download/{id}` - Download tugas
- `POST /kirim-jawaban` - Kirim jawaban tugas

### Try Out Siswa
- `GET /try-out-siswa` - Daftar try-out untuk siswa
- `GET /try-out/{tryOut}/kerjakan` - Kerjakan try-out
- `POST /try-out/jawaban/save` - Simpan jawaban sementara
- `POST /try-out/finalize` - Finalisasi try-out
- `GET /try-out/hasil/{hasilTryOut}` - Lihat hasil try-out

### Penilaian Siswa
- `GET /siswa/penilaian` - Daftar penilaian siswa
- `GET /siswa/penilaian/{penilaian}` - Detail nilai
- `GET /penilaian/export-pdf` - Ekspor nilai PDF

## 4. Role: Orang Tua
- `GET /orangtua/dashboard` - Dashboard orang tua
- `GET /orangtua/notifikasi` - Daftar notifikasi orang tua
- `GET /orangtua/tugas/siswa` - Lihat tugas siswa

## 5. Role: Admin
### Dashboard & Master Data
- `GET /admin/dashboard` - Dashboard admin
- `GET /jurusan` - Daftar jurusan
- `GET /mapel` - Daftar mata pelajaran
- `GET /guru` - Daftar guru
- `GET /kelas` - Daftar kelas
- `GET /siswa` - Daftar siswa
- `GET /user` - Daftar user
- `GET /jadwal` - Daftar jadwal
- `GET /pengumuman-sekolah` - Daftar pengumuman sekolah
- `GET /ppdb-year` - Daftar PPDB tahunan
- `POST /ppdb-year/import` - Import PPDB dari URL JSON
- `GET /pengaturan` - Daftar pengaturan

## 6. Catatan Implementasi
- Semua rute admin dilindungi middleware `auth` dan `checkRole:admin`.
- Rute guru, siswa, dan orang tua dilindungi oleh `auth` plus middleware role masing-masing.
- Rute PPDB tahunan menggunakan controller `PpdbYearController` dengan resource CRUD dan impor data web.

## 7. Format Payload Penting
### Input PPDB Import
- `source_url`: URL JSON sumber data

### Input Materi / Tugas
- `judul`, `deskripsi`, `file`, `guru_id`, `kelas_id`

### Input Try Out
- `guru_id`, `mapel_id`, `kelas_id`, `judul`, `deskripsi`, `waktu_mulai`, `waktu_selesai`, `durasi_menit`, `jumlah_soal`, `acak_soal`, `acak_opsi`

### Input Penilaian
- `guru_id`, `siswa_id`, `kelas_id`, `mapel_id`, `nilai_harian`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_sikap`, `nilai_kehadiran`

## 8. Tips Penggunaan Dokumentasi API
- Gunakan route names di Blade untuk rute resource Laravel.
- Pastikan pengguna memiliki role benar sebelum mengakses endpoint.
- Untuk pengembangan lebih lanjut, gunakan `routes:list` untuk daftar API lengkap.
