<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ app('settings')['theme'] }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Laravel') }}</title>
  @vite(['resources/css/modern.style.css', 'resources/js/app.js'])
  <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
  <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
  <link rel="manifest" href="/favicon/site.webmanifest">

</head>

<body class="d-flex flex-column min-vh-100">
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center fs-4" href="{{ url('/') }}">
        <i class="bi bi-boxes text-primary me-2"></i>
        <span class="fw-semibold">{{ config('app.name') }}</span>
      </a>
      <div class="d-flex gap-2">
        @auth
          <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-grid me-2"></i>Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
            <i class="ti ti-login"></i>
            Login Admin
          </a>
          {{-- <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Get started</a> --}}
        @endauth
      </div>
    </div>
  </nav>

  <main class="flex-shrink-0">
    <div class="px-4 py-5 text-center">
      <div class="col-lg-6 mx-auto">
        <h1 class="display-5 fw-bold text-body-emphasis mb-3">Mulai Pendaftaran Secara Online</h1>
        <p class="lead mb-4">
          Disini anda akan dipandu secara online untuk memulai pendaftaran perkara di
          <strong> {{ app('settings')['satker'] }}.</strong>
        </p>
        <div class="d-flex justify-content-center gap-2">
          <dotlottie-player src="https://lottie.host/5285ca0d-4adf-4d08-917b-ddf708574e91/uSH2hjrWEU.lottie"
            background="transparent" speed="1" style="margin-top:-100px" loop autoplay></dotlottie-player>
        </div>
        <div class="d-flex justify-content-center gap-2">
          <a href="{{ url('step-1') }}" class="btn btn-lg  btn-danger px-4">
            <i class="ti ti-flag me-2"></i>Mulai
          </a>
          <a href="{{ route('search') }}" class="btn btn-lg btn-outline-dark px-4">
            <i class="ti ti-key me-2"></i>Cek Pendaftaran
          </a>
        </div>
      </div>
    </div>

    <div class="container py-3 bg-white rounded-5">
      <div class="text-center mb-5">
        <h4>Langkah-langkah mudah mendaftar perkara secara online</h4>
      </div>
      <div class="row g-4 row-cols-1 row-cols-md-5 text-center">
        <div class="col">
          <img src="{{ url('/images/animation/notebook.gif') }}" width="100" alt="illus-notebook">
          <h3>Isi Data Diri</h3>
          <p class="text-muted">Isi data diri anda untuk membuat akun ecourt disini. Setelah itu, admin akan memberikan
            user dan password.</p>
        </div>
        <div class="col">
          <img src="{{ url('/images/animation/social-media.gif') }}" width="100" alt="illus-social-media">
          <h3>Masuk ke Ecourt</h3>
          <p class="text-muted">Gunakan user dan password tersebut untuk masuk ke aplikasi Ecourt Mahkamah Agung
            <strong> <a href="https://ecourt.mahkamahagung.go.id/Login">Disini</a></strong>
          </p>
        </div>
        <div class="col">
          <img src="{{ url('/images/animation/checklist.gif') }}" width="100" alt="illus-checklist">
          <h3>Lengkapi Data </h3>
          <p class="text-muted">Lengkapi data gugatan atau permohonan anda dan unggah dokumen yang dibutuhkan.
          </p>
        </div>
        <div class="col">
          <img src="{{ url('/images/animation/payment.gif') }}" width="100" alt="illus-payment">
          <h3>Bayar Biaya Panjar </h3>
          <p class="text-muted">Silahkan bayar biaya panjar pendaftaran anda menggunakan virtual account yang tercantum
            setelah kelengkapan data selesai.
          </p>
        </div>
        <div class="col">
          <img src="{{ url('/images/animation/calendar.gif') }}" width="100" alt="illus-calendar">
          <h3>Pantau Proses Perkara</h3>
          <p class="text-muted">Tunggu panggilan sidang yang akan diumumkan melalui e-mail atau whatsapp.
          </p>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer mt-auto py-2 border-top">
    <div class="container text-center">
      <span class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
    </div>
  </footer>
</body>

</html>
