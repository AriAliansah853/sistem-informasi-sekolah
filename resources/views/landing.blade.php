@extends('layouts.front')
@section('title', $pengaturan->name ?? 'Landing Page')

@section('content')
<div class="landing-hero" style="background-image: url('{{ $pengaturan->hero_image ? URL::asset($pengaturan->hero_image) : asset('assets/img/example-image.jpg') }}')">
  @if(!empty($pengaturan->hero_video))
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100 hero-video">
      <source src="{{ URL::asset($pengaturan->hero_video) }}" type="video/mp4">
    </video>
  @endif
  <div class="container position-relative" style="z-index: 2;">
    <div class="row align-items-center">
      <div class="col-lg-6 hero-content scroll-reveal">
        <span class="badge bg-white text-primary mb-3">Sekolah Unggul</span>
        <h1 class="display-5 fw-bold">{{ $pengaturan->hero_title ?? 'Sistem Informasi Sekolah Modern' }}</h1>
        <p class="lead mt-3 text-white-75">{{ $pengaturan->hero_subtitle ?? 'Solusi digital untuk manajemen sekolah yang profesional, transparan, dan ramah pengguna.' }}</p>
        <div class="mt-4 flex-wrap gap-3">
          <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Daftar PPDB</a>
          <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
          <a href="#gallery" class="btn btn-outline-light btn-lg">Virtual Tour</a>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1 d-none d-lg-block scroll-reveal">
        <div class="card landing-card overflow-hidden">
          <img src="{{ $pengaturan->hero_image ? URL::asset($pengaturan->hero_image) : asset('assets/img/example-image.jpg') }}" alt="Sekolah" class="img-fluid hero-image">
        </div>
      </div>
    </div>
  </div>
</div>

<section id="stats" class="py-5 bg-white">
  <div class="container">
    <div class="row gy-4 text-center">
      <div class="col-md-3 scroll-reveal">
        <div class="card stats-card p-4 h-100">
          <h2 class="display-5 fw-bold">520+</h2>
          <p class="mb-0 text-muted">Siswa Aktif</p>
        </div>
      </div>
      <div class="col-md-3 scroll-reveal">
        <div class="card stats-card p-4 h-100">
          <h2 class="display-5 fw-bold">72</h2>
          <p class="mb-0 text-muted">Guru Profesional</p>
        </div>
      </div>
      <div class="col-md-3 scroll-reveal">
        <div class="card stats-card p-4 h-100">
          <h2 class="display-5 fw-bold">180+</h2>
          <p class="mb-0 text-muted">Prestasi Nasional</p>
        </div>
      </div>
      <div class="col-md-3 scroll-reveal">
        <div class="card stats-card p-4 h-100">
          <h2 class="display-5 fw-bold">98%</h2>
          <p class="mb-0 text-muted">Tingkat Kelulusan</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="profile" class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 scroll-reveal">
        <h2 class="fw-bold">Profil Sekolah</h2>
        <p class="text-muted">{{ $pengaturan->about_description ?? 'Sekolah ini dibangun untuk mendukung pengembangan akademik dan karakter siswa melalui fasilitas modern dan tenaga pendidik profesional.' }}</p>
        <ul class="list-unstyled mt-4">
          <li class="mb-3"><strong>✔</strong> Standar internasional dengan lingkungan belajar inklusif.</li>
          <li class="mb-3"><strong>✔</strong> Sistem manajemen sekolah digital kelas dunia.</li>
          <li class="mb-3"><strong>✔</strong> Fokus pada persiapan kompetisi global dan karir masa depan.</li>
        </ul>
      </div>
      <div class="col-lg-6 scroll-reveal">
        <div class="card landing-card p-4 h-100">
          <div class="card-body">
            <h5 class="fw-bold">Mengapa Memilih Kami?</h5>
            <div class="mt-4">
              <div class="d-flex align-items-start mb-3">
                <span class="feature-icon me-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a5 5 0 0 0-5 5v1H1.5A1.5 1.5 0 0 0 0 7.5v1A1.5 1.5 0 0 0 1.5 10H3v1a5 5 0 0 0 10 0v-1h1.5A1.5 1.5 0 0 0 16 9v-.5A1.5 1.5 0 0 0 14.5 7H14V5a5 5 0 0 0-6-5z"/></svg></span>
                <div>
                  <h6 class="mb-1">Teknologi Terintegrasi</h6>
                  <p class="text-muted small mb-0">Platform pembelajaran dan administrasi terhubung dalam satu sistem.</p>
                </div>
              </div>
              <div class="d-flex align-items-start mb-3">
                <span class="feature-icon me-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M7.247 4.86l-4.796-.696L1.28 8.065.073 10.626a.5.5 0 0 0 .434.725h4.1l1.455 4.41a.5.5 0 0 0 .948 0l1.455-4.41h4.1a.5.5 0 0 0 .434-.725l-1.207-2.56-4.796-.696a.5.5 0 0 1-.286-.196L8 3.103l-1.027 1.561a.5.5 0 0 1-.726.196z"/></svg></span>
                <div>
                  <h6 class="mb-1">Kurikulum Berorientasi Karir</h6>
                  <p class="text-muted small mb-0">Pembelajaran terstruktur untuk kompetensi abad 21.</p>
                </div>
              </div>
              <div class="d-flex align-items-start">
                <span class="feature-icon me-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path d="M1 5.5A1.5 1.5 0 0 1 2.5 4h11A1.5 1.5 0 0 1 15 5.5v7A1.5 1.5 0 0 1 13.5 14h-11A1.5 1.5 0 0 1 1 12.5v-7z"/><path d="M4.5 1h7a.5.5 0 0 1 .5.5v3h-8v-3a.5.5 0 0 1 .5-.5z"/></svg></span>
                <div>
                  <h6 class="mb-1">Dukungan Alumni</h6>
                  <p class="text-muted small mb-0">Jaringan alumni aktif dan mentoring karir pasca kelulusan.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="program" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Program Unggulan</h2>
      <p class="text-muted">Program kelas dunia untuk memperkuat kompetensi akademik dan karakter siswa.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card landing-feature p-4 h-100">
          <h5 class="fw-semibold">Program STEAM</h5>
          <p class="text-muted small">Integrasi sains, teknologi, teknik, seni, dan matematika untuk inovasi.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card landing-feature p-4 h-100">
          <h5 class="fw-semibold">Bilingual Learning</h5>
          <p class="text-muted small">Pembelajaran bahasa Inggris dan Bahasa Indonesia di semua mata pelajaran.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card landing-feature p-4 h-100">
          <h5 class="fw-semibold">Program Vokasi Digital</h5>
          <p class="text-muted small">Kelas coding, desain grafis, dan pengembangan aplikasi untuk masa depan karir.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="prestasi" class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Prestasi Siswa</h2>
      <p class="text-muted">Pencapaian akademik dan non-akademik yang menunjukkan kualitas pendidikan kami.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card landing-project p-4 h-100">
          <span class="feature-icon mb-3 d-inline-flex"><strong>01</strong></span>
          <h5 class="fw-semibold">Juara Olimpiade Matematika</h5>
          <p class="text-muted small">Siswa kami meraih medali emas di kompetisi regional dan nasional.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card landing-project p-4 h-100">
          <span class="feature-icon mb-3 d-inline-flex"><strong>02</strong></span>
          <h5 class="fw-semibold">Inovasi Teknologi</h5>
          <p class="text-muted small">Tim robotika sekolah berhasil maju ke babak final tingkat provinsi.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card landing-project p-4 h-100">
          <span class="feature-icon mb-3 d-inline-flex"><strong>03</strong></span>
          <h5 class="fw-semibold">Prestasi Seni</h5>
          <p class="text-muted small">Karya seni siswa dipamerkan dalam event seni budaya tingkat nasional.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="gallery" class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Galeri Kegiatan</h2>
      <p class="text-muted">Momen pembelajaran, kegiatan ekstrakurikuler, dan inovasi siswa dalam suasana modern.</p>
    </div>
    <div class="row g-3">
      @foreach(range(1,6) as $image)
      <div class="mt-4 col-12 col-sm-6 col-lg-4 scroll-reveal">
        <div class="gallery-item">
          <img src="{{ $pengaturan->hero_image ? URL::asset($pengaturan->hero_image) : asset('assets/img/example-image.jpg') }}" alt="Galeri Kegiatan {{ $image }}" class="img-fluid">
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section id="news" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Berita & Pengumuman</h2>
      <p class="text-muted">Informasi terbaru tentang kegiatan sekolah, pendaftaran, dan agenda penting.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 scroll-reveal">
        <div class="card landing-card h-100 p-4">
          <span class="badge bg-primary text-white mb-3">PPDB</span>
          <h5 class="fw-semibold">Pendaftaran Online Telah Dibuka</h5>
          <p class="text-muted small">Daftar sekarang untuk mendapatkan informasi seleksi dan jadwal kegiatan orientasi.</p>
        </div>
      </div>
      <div class="col-md-4 scroll-reveal">
        <div class="card landing-card h-100 p-4">
          <span class="badge bg-info text-white mb-3">Kegiatan</span>
          <h5 class="fw-semibold">Workshop Literasi Digital</h5>
          <p class="text-muted small">Siswa mengikuti pelatihan teknologi terbaru bersama pakar industri.</p>
        </div>
      </div>
      <div class="col-md-4 scroll-reveal">
        <div class="card landing-card h-100 p-4">
          <span class="badge bg-success text-white mb-3">Pengumuman</span>
          <h5 class="fw-semibold">Jadwal Ujian Semester</h5>
          <p class="text-muted small">Pengumuman jadwal ujian dan petunjuk pelaksanaan untuk siswa kelas X hingga XII.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="testimonials" class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Testimoni Alumni</h2>
      <p class="text-muted">Lulusan kami berbagi pengalaman tentang kualitas pendidikan dan dukungan sekolah.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card testimonial-card p-4 h-100">
          <div class="card-body">
            <div class="quote-mark">“</div>
            <p class="text-muted">Sekolah ini membentuk saya menjadi pribadi percaya diri dan siap bersaing di universitas internasional.</p>
            <h6 class="fw-bold mt-4 mb-1">Aulia Rahma</h6>
            <p class="small text-muted">Alumni 2023</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card testimonial-card p-4 h-100">
          <div class="card-body">
            <div class="quote-mark">“</div>
            <p class="text-muted">Fasilitas digital dan pembelajaran interaktif membuat proses belajar menjadi lebih menarik setiap hari.</p>
            <h6 class="fw-bold mt-4 mb-1">Bima Putra</h6>
            <p class="small text-muted">Alumni 2022</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 scroll-reveal">
        <div class="card testimonial-card p-4 h-100">
          <div class="card-body">
            <div class="quote-mark">“</div>
            <p class="text-muted">Guru-guru mendukung seluruh bakat siswa, baik akademik maupun kreativitas seni dan olahraga.</p>
            <h6 class="fw-bold mt-4 mb-1">Nadia Salsabila</h6>
            <p class="small text-muted">Alumni 2024</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="ppdb" class="py-5 bg-primary text-white">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-7 scroll-reveal">
        <h2 class="fw-bold">PPDB Online 2026</h2>
        <p class="lead text-white-75">Proses pendaftaran digital yang mudah, aman, dan mendukung kebutuhan calon siswa serta orang tua.</p>
        <ul class="list-unstyled mt-4 text-white-75">
          <li class="mb-3">✔ Pendaftaran online 24/7</li>
          <li class="mb-3">✔ Proses verifikasi cepat dan transparan</li>
          <li class="mb-3">✔ Dukungan informasi lengkap selama pendaftaran</li>
        </ul>
        <div class="mt-4">
          <a href="{{ route('ppdb.register') }}" class="btn btn-light btn-lg me-2">Daftar PPDB</a>
          <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
        </div>
      </div>
      <div class="col-lg-5 scroll-reveal">
        <div class="card bg-white text-dark landing-card p-4 h-100">
          <div class="card-body text-center">
            <h5 class="fw-bold">Virtual Tour Sekolah</h5>
            <p class="text-muted">Lihat langsung suasana sekolah, ruang kelas, dan fasilitas unggulan kami.</p>
            <a href="#gallery" class="btn btn-primary btn-lg">Mulai Virtual Tour</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="faq" class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Pertanyaan Umum</h2>
      <p class="text-muted">Jawaban cepat untuk topik pendaftaran, kurikulum, dan fasilitas sekolah.</p>
    </div>
    <div class="accordion" id="faqAccordion">
      <div class="accordion-item faq-card scroll-reveal">
        <h2 class="accordion-header" id="faqOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            Bagaimana cara mendaftar PPDB online?
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">Kunjungi halaman PPDB, isi formulir pendaftaran, unggah dokumen, dan selesaikan proses verifikasi sesuai panduan yang tersedia.</div>
        </div>
      </div>
      <div class="accordion-item faq-card scroll-reveal">
        <h2 class="accordion-header" id="faqTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Program unggulan apa saja yang tersedia?
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">Kami menawarkan program STEAM, bahasa internasional, teknologi digital, dan vokasi kreatif yang dirancang untuk kesiapan masa depan.</div>
        </div>
      </div>
      <div class="accordion-item faq-card scroll-reveal">
        <h2 class="accordion-header" id="faqThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Apakah ada beasiswa dan dukungan finansial?
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">Ya, sekolah menyediakan informasi beasiswa prestasi dan bantuan biaya untuk siswa berprestasi dan keluarga yang membutuhkan.</div>
        </div>
      </div>
      <div class="accordion-item faq-card scroll-reveal">
        <h2 class="accordion-header" id="faqFour">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            Bagaimana saya bisa menghubungi pihak sekolah?
          </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
          <div class="accordion-body text-muted">Silakan gunakan formulir kontak atau langsung hubungi alamat email dan telepon yang tercantum di bagian kontak.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="contact" class="py-5 bg-light">
  <div class="container">
    <div class="row g-4 align-items-start">
      <div class="col-lg-5 scroll-reveal">
        <div class="card landing-card p-4 h-100">
          <div class="card-body">
            <h2 class="fw-bold">Kontak & Lokasi</h2>
            <p class="text-muted">Hubungi tim administrasi kami untuk pendaftaran, tur sekolah, atau informasi lebih detail.</p>
            <div class="mt-4">
              <p class="mb-2"><strong>Alamat</strong><br>{{ $pengaturan->contact_address ?? 'Jl. Contoh No. 1, Kecamatan, Kota' }}</p>
              <p class="mb-2"><strong>Email</strong><br>{{ $pengaturan->contact_email ?? 'info@sekolah.com' }}</p>
              <p class="mb-0"><strong>Telepon</strong><br>{{ $pengaturan->contact_phone ?? '0812-3456-7890' }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 scroll-reveal">
        <div class="map-responsive">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31690.713495319906!2d106.7774009!3d-6.2146202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5ea5a0c2eb5%3A0x7b9c2d91d27f4b8a!2sJakarta!5e0!3m2!1sen!2sid!4v1690000000000" width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="py-5 bg-primary text-white">
  <div class="container">
    <div class="row gy-4">
      <div class="col-md-5">
        <h4 class="fw-bold">{{ $pengaturan->name ?? 'Sekolah Modern' }}</h4>
        <p class="text-white-75">Sistem Informasi Sekolah modern untuk siswa, guru, orang tua, dan komunitas sekolah.</p>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold">Navigasi</h6>
        <ul class="list-unstyled text-white-75">
          <li><a href="#about" class="text-white text-decoration-none">Tentang</a></li>
          <li><a href="#fasilitas" class="text-white text-decoration-none">Fasilitas</a></li>
          <li><a href="#ppdb" class="text-white text-decoration-none">PPDB</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="fw-bold">Info Cepat</h6>
        <p class="text-white-75 small mb-0">Pelayanan informasi sekolah, pendaftaran, dan dukungan siswa dengan teknis modern dan ramah pengguna.</p>
      </div>
    </div>
    <div class="text-center mt-4 text-white-50 small">© {{ date('Y') }} {{ $pengaturan->name ?? 'Sekolah Modern' }}. Semua hak dilindungi.</div>
  </div>
</footer>
@endsection

@push('script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const revealItems = document.querySelectorAll('.scroll-reveal');
    const revealOnScroll = function () {
      const windowHeight = window.innerHeight;
      revealItems.forEach(item => {
        const position = item.getBoundingClientRect().top;
        if (position < windowHeight - 80) {
          item.classList.add('visible');
        }
      });
    };
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
  });
</script>
@endpush
