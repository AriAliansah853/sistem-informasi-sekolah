<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title') | {{ optional($pengaturan)->name ?? config('app.name') }}</title>

  {{-- Styling --}}
  @include('includes.style')
  @stack('style')

  <style>
    body {
      background: #f8fafc;
      color: #1f2937;
      scroll-behavior: smooth;
    }
    .landing-hero {
      min-height: 92vh;
      display: flex;
      align-items: center;
      background-size: cover;
      background-position: center;
      position: relative;
      color: #fff;
      overflow: hidden;
    }
    .landing-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(15, 23, 42, 0.78), rgba(29, 78, 216, 0.65));
      z-index: 1;
    }
    .landing-hero .hero-content {
      position: relative;
      z-index: 2;
    }
    .landing-hero .hero-video,
    .landing-hero .hero-image {
      border-radius: 1.75rem;
      box-shadow: 0 35px 80px rgba(15, 23, 42, 0.24);
    }
    .landing-hero .hero-video {
      width: 100%;
      height: auto;
      max-height: 520px;
      object-fit: cover;
    }
    .landing-card {
      border: none;
      border-radius: 1.5rem;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
    }
    .landing-card .card-body {
      padding: 2rem;
    }
    .landing-feature,
    .landing-project,
    .landing-facility,
    .landing-testimonial {
      border-radius: 1.5rem;
      transition: transform .35s ease, box-shadow .35s ease;
    }
    .landing-feature:hover,
    .landing-project:hover,
    .landing-facility:hover,
    .landing-testimonial:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 50px rgba(15, 23, 42, 0.14);
    }
    .stats-card {
      border-radius: 1.5rem;
      background: #fff;
      box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
    }
    .btn-primary {
      background: #1d4ed8;
      border-color: #1d4ed8;
    }
    .btn-primary:hover {
      background: #1e40af;
      border-color: #1e40af;
    }
    .btn-outline-primary {
      border-color: rgba(29, 78, 216, 0.35);
      color: #1d4ed8;
    }
    .btn-outline-primary:hover {
      background: rgba(29, 78, 216, 0.08);
    }
    .section-title h2 {
      letter-spacing: -0.03em;
    }
    .section-title p,
    .text-muted {
      color: #6b7280;
    }
    .feature-icon {
      width: 56px;
      height: 56px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 1rem;
      background: rgba(29, 78, 216, 0.08);
      color: #1d4ed8;
    }
    .section-divider {
      width: 72px;
      height: 4px;
      background: #1d4ed8;
      border-radius: 999px;
      margin: 0.75rem auto 1.75rem;
    }
    .gallery-item {
      border-radius: 1.5rem;
      overflow: hidden;
      position: relative;
    }
    .gallery-item img {
      width: 100%;
      height: auto;
      transition: transform .4s ease;
    }
    .gallery-item:hover img {
      transform: scale(1.05);
    }
    .faq-card {
      border-radius: 1.5rem;
      border: 1px solid rgba(148, 163, 184, 0.18);
    }
    .testimonial-card {
      border-radius: 1.75rem;
      background: #fff;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.07);
    }
    .testimonial-card .card-body {
      position: relative;
    }
    .testimonial-card .quote-mark {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      font-size: 2.2rem;
      color: rgba(29, 78, 216, 0.16);
    }
    .map-responsive {
      border-radius: 1.5rem;
      overflow: hidden;
      min-height: 320px;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.1);
    }
    .scroll-reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity .65s ease, transform .65s ease;
    }
    .scroll-reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('landing') }}">
        <img src="{{ URL::asset(optional($pengaturan)->logo) ?? asset('assets/img/logo.png') }}" alt="Logo" height="40" class="me-2">
        {{ optional($pengaturan)->name ?? config('app.name') }}
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="#visi-misi">Visi & Misi</a></li>
          <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
          @guest
            <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-3" href="{{ route('login') }}">Masuk</a></li>
          @else
            <li class="nav-item"><a class="nav-link btn btn-outline-primary ms-3" href="{{ route('home') }}">Dashboard</a></li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <main>
    @yield('content')
  </main>

  @include('includes.script')
  @stack('script')
</body>
</html>
