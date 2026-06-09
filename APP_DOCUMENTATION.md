# Dokumentasi Aplikasi Sistem Informasi Sekolah

## 1. Ringkasan Aplikasi
Aplikasi ini adalah Sistem Informasi Sekolah berbasis Laravel 11 yang mendukung manajemen sekolah modern dengan role-based access control. Aplikasi menyediakan fitur untuk admin, guru, siswa, dan orang tua, serta landing page publik dengan informasi PPDB, profil sekolah, fasilitas, prestasi, dan berita.

## 2. Teknologi Utama
- Laravel 11 (PHP 8.2)
- Blade templating engine
- Bootstrap 5
- Laravel UI
- Laravel Sanctum
- Guzzle HTTP
- Dompdf
- Laravel Mix, Sass, Axios, Popper

## 3. Arsitektur Sistem
Aplikasi dibangun dengan pola MVC standar Laravel:
- Models: representasi data dan relasi ke tabel database
- Controllers: logika aplikasi dan pemrosesan request
- Views: tampilan antarmuka menggunakan Blade
- Middleware: kontrol akses per role

### 3.1 Struktur Route
Rute utama diatur pada `routes/web.php`:
- Public: `/` landing page
- Auth: login/register, profil, ganti password
- Role-based routes:
  - `checkRole:admin`
  - `checkRole:guru`
  - `checkRole:siswa`
  - `checkRole:orangtua`

### 3.2 Middleware `CheckRole`
File: `app/Http/Middleware/CheckRole.php`
- Memeriksa atribut `roles` pada user
- Mengarahkan kembali bila role tidak cocok

## 4. User Roles dan Fitur
### 4.1 Admin
Admin memiliki akses penuh untuk:
- Manajemen jurusan, mapel, guru, kelas, siswa
- Manajemen jadwal dan pengumuman
- Manajemen pengguna
- Konsol PPDB tahunan dengan import web JSON
- Pengaturan aplikasi

### 4.2 Guru
Guru dapat:
- Melihat dashboard guru
- Mengelola materi dan tugas
- Mengisi absensi, laporan, dan ekspor ke Excel/PDF
- Download jawaban siswa
- Melakukan penilaian siswa
- Menyusun bank soal dan try-out
- Melihat hasil try-out dan statistik

### 4.5 PPDB Online
Calon siswa dapat mendaftar langsung dari website menggunakan formulir PPDB, dan data pendaftaran dikirim ke dashboard admin untuk ditindaklanjuti.

### 4.3 Siswa
Siswa dapat:
- Mengakses dashboard siswa
- Mengakses materi dan mendownload file
- Mengakses tugas dan mengirim jawaban
- Mengerjakan try-out online
- Melihat hasil try-out
- Melihat penilaian pribadi

### 4.4 Orang Tua
Orang tua dapat:
- Mengakses dashboard orang tua
- Melihat notifikasi
- Melihat tugas siswa yang diasuh

## 5. Modul Utama
Berikut adalah modul utama beserta tujuannya:

- `LandingController`: halaman depan publik
- `HomeController`: dashboard per role
- `UserController`: profil dan pengaturan password
- `GuruController`, `KelasController`, `JurusanController`, `MapelController`, `SiswaController`: master data
- `JadwalController`: manajemen jadwal akademik
- `PengumumanSekolahController`: pengumuman sekolah
- `MateriController`: modul materi pembelajaran
- `TugasController`: modul tugas sekolah
- `AbsensiController`: absensi guru dan laporan
- `BankSoalController`: manajemen bank soal untuk CBT
- `TryOutController`, `TryOutJawabanController`, `HasilTryOutController`: try-out dan hasil
- `PenilaianController`: penilaian siswa
- `PpdbYearController`: data PPDB tahunan dan impor web
- `PengaturanController`: pengaturan aplikasi

## 6. Flowchart Aplikasi
Flowchart berikut menggambarkan aliran utama aplikasi dari pengguna ke modul yang relevan.

- File flowchart SVG: `docs/flowchart.svg`
- File flowchart PNG: `docs/flowchart.png`
- File ERD image SVG: `docs/erd.svg`
- File ERD image PNG: `docs/erd.png`
- Dokumen API internal: `APP_API.md`

> Catatan: Diagram sudah tersedia dalam format SVG dan PNG. File bisa dibuka langsung di browser atau editor gambar.
## 7. Proses Alur Pengguna
1. Pengunjung membuka halaman landing.
2. Jika sudah terdaftar, pengguna login.
3. Setelah otentikasi sukses, pengguna diarahkan ke dashboard sesuai role.
4. Role admin membuka data master, PPDB, dan pengaturan.
5. Role guru mengelola materi, tugas, absensi, dan try-out.
6. Role siswa mengakses materi, menyerahkan tugas, dan mengerjakan try-out.
7. Role orang tua melihat notifikasi dan prestasi siswa.

## 8. Database & Model Utama
Model yang digunakan di aplikasi mencakup:
- `User`
- `Guru`
- `Siswa`
- `Kelas`
- `Jurusan`
- `Mapel`
- `Materi`
- `Tugas`
- `Absensi`
- `BankSoal`
- `TryOut`
- `HasilTryOut`
- `JawabanSiswa`
- `PenilaianSiswa`
- `PengumumanSekolah`
- `PpdbYear`
- `Pengaturan`

## 9. Panduan Installasi
1. Salin `.env.example` ke `.env`.
2. Jalankan `composer install`.
3. Jalankan `npm install`.
4. Jalankan `php artisan key:generate`.
5. Sesuaikan konfigurasi database di `.env`.
6. Jalankan `php artisan migrate --seed`.
7. Jalankan `npm run dev`.
8. Jalankan server dengan `php artisan serve`.

## 10. Tips Penggunaan
- Gunakan role admin untuk mengelola data master terlebih dahulu.
- Pastikan data guru, mapel, kelas, dan siswa telah terisi sebelum membuat jadwal atau penilaian.
- Untuk PPDB tahunan, gunakan fitur import JSON sebagai sumber data web jika tersedia.
- Selalu periksa halaman `profile` untuk memperbarui password dan informasi user.
