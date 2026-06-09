# Product Requirements Document (PRD)

## 1. Judul Produk
Sistem Informasi Sekolah Terintegrasi dengan Modul Pembelajaran, PPDB, dan CBT.

## 2. Latar Belakang
Sekolah membutuhkan aplikasi digital yang menyatukan manajemen akademik, komunikasi orang tua, pembelajaran daring, dan data penerimaan siswa baru. Aplikasi ini dirancang untuk mendukung operasional harian sekolah secara modern dan efisien.

## 3. Tujuan Produk
- Mempermudah manajemen tenaga pendidik, siswa, kelas, dan jadwal.
- Mengintegrasikan materi, tugas, dan try-out dalam satu platform.
- Menyediakan dashboard per role untuk admin, guru, siswa, dan orang tua.
- Menyediakan landing page publik dengan informasi profil sekolah, fasilitas, berita, dan PPDB.
- Menyediakan fitur PPDB tahunan yang dapat diimpor dari web.

## 4. Scope MVP
### 4.1 Fungsi Utama
- Autentikasi user dan manajemen profil.
- Dashboard role-based untuk admin, guru, siswa, dan orang tua.
- Master data: jurusan, mapel, guru, kelas, siswa, user.
- Modul jadwal kelas.
- Pengumuman sekolah.
- Modul materi pembelajaran dan tugas.
- Absensi guru dan ekspor laporan.
- Sistem penilaian siswa.
- Bank soal, try-out, dan hasil evaluasi.
- PPDB tahunan dengan CRUD dan impor URL.
- Formulir PPDB online untuk calon siswa, dan admin menerima data pendaftaran langsung.
- Halaman landing publik.

### 4.2 Batasan
- Fitur finansial / pembayaran tidak tersedia.
- Fitur chat real-time tidak termasuk.
- Integrasi API eksternal hanya untuk impor PPDB dan file download internal.

## 5. Persona Pengguna
### 5.1 Admin Sekolah
- Kebutuhan: kelola guru, siswa, jadwal, pengumuman, dan pengaturan sekolah.
- Goal: memastikan data sekolah lengkap dan terstruktur.

### 5.2 Guru
- Kebutuhan: memasukkan materi, tugas, absensi, penilaian, dan try-out.
- Goal: memudahkan manajemen pembelajaran dan evaluasi.

### 5.3 Siswa
- Kebutuhan: mengakses materi, tugas, dan try-out.
- Goal: belajar mandiri dan melihat hasil akademik.

### 5.4 Orang Tua
- Kebutuhan: memantau notifikasi dan tugas anak.
- Goal: tetap terhubung dengan perkembangan anak.

## 6. Fitur Detil
### 6.1 Autentikasi & Profil
- Login, logout, register.
- Edit profil dan ubah password.
- Redirect ke dashboard per role setelah login.

### 6.2 Admin
- CRUD jurusan, mapel, guru, kelas, siswa, user, jadwal, pengumuman.
- CRUD PPDB tahunan.
- Import data PPDB dari URL JSON.
- Pengaturan aplikasi.

### 6.3 Guru
- Akses dashboard guru.
- CRUD materi dan tugas.
- Kelola absensi dan generate laporan Excel/PDF.
- Penilaian siswa dengan rekap.
- CRUD bank soal dan try-out.
- Tampilan hasil try-out dan grafik statistik.

### 6.4 Siswa
- Akses dashboard siswa.
- Lihat daftar materi dan download file.
- Lihat dan kirim tugas.
- Kerjakan try-out online.
- Lihat hasil try-out dan penilaian.

### 6.5 Orang Tua
- Akses dashboard orang tua.
- Lihat notifikasi orang tua.
- Lihat tugas siswa.

## 7. Produk Harus
- Menjaga akses berdasar role dengan middleware `checkRole`.
- Menyediakan UI Bootstrap yang responsif.
- Support ekspor data absensi ke Excel dan PDF.
- Menyimpan data import PPDB lengkap dengan metadata URL.
- Menyediakan halaman landing publik modern.

## 8. Alur Pengembangan
### 8.1 Fase 1: Setup & Infrastruktur
- Siapkan Laravel 11 dan konfigurasi environment.
- Buat model dan migrasi utama.
- Implementasikan autentikasi dan middleware role.

### 8.2 Fase 2: Admin & Master Data
- Buat CRUD jurusan, mapel, guru, kelas, siswa, user.
- Buat manajemen jadwal dan pengumuman.
- Bangun dashboard admin.

### 8.3 Fase 3: Pembelajaran & Evaluasi
- Tambahkan materi, tugas, dan absensi.
- Implementasikan penilaian siswa.
- Tambahkan bank soal, try-out, dan hasil.

### 8.4 Fase 4: Siswa & Orang Tua
- Buat dashboard siswa dan orang tua.
- Tambahkan akses materi, tugas, try-out, dan notifikasi.

### 8.5 Fase 5: Landing Page & PPDB
- Bangun landing page edukasi dengan informasi sekolah.
- Tambahkan fitur PPDB tahunan dan import data web.

### 8.6 Fase 6: Quality & Go-Live
- Uji fungsionalitas role-based.
- Uji eksport/import.
- Uji tampilan responsif.
- Deploy ke lingkungan produksi.

## 9. Requirement Teknis
- PHP 8.2
- Laravel 11
- MySQL/MariaDB atau database lain yang didukung
- NPM dan Laravel Mix untuk aset frontend
- `composer install`, `npm install`, `php artisan migrate`, `npm run dev`

## 10. Success Metrics
- 100% fitur admin CRUD terimplementasi.
- 100% role-specific dashboard berfungsi.
- Landing page informatif dan responsif.
- PPDB import sukses tanpa error dan dapat dilihat admin.
- User dapat melakukan try-out dan melihat hasil.

## 11. Risiko dan Mitigasi
- Risiko: hak akses lepas. Mitigasi: validasi middleware `checkRole`.
- Risiko: data PPDB inconsistency. Mitigasi: simpan `source_url` dan raw JSON.
- Risiko: tampilan mobile buruk. Mitigasi: gunakan Bootstrap 5 grid responsif.

## 12. Dokumentasi Tambahan
- File dokumentasi utama: `APP_DOCUMENTATION.md`
- PRD produk: `APP_PRD.md`
- Flowchart menggunakan Mermaid telah dimasukkan di `APP_DOCUMENTATION.md`
