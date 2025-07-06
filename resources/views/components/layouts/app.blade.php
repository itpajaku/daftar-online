<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ app('settings')['theme'] }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  @vite(['resources/css/app.scss', 'resources/js/app.js'])
  <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
  <link rel="manifest" href="/favicon/site.webmanifest">
</head>

<body class="min-vh-100">
  <!-- Sidebar -->
  <div class="d-flex min-vh-100">
    <div class="sidebar border-end position-fixed h-100 d-none d-lg-block" style="width: 280px">
      {{ $sidebar ?? '' }}
    </div>
    <div class="sidebar-mobile position-fixed h-100 bg-light border-end collapse d-lg-none"
      style="width: 280px; z-index: 1045" id="sidebar">
      {{ $sidebar ?? '' }}
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <div class="ps-lg-280px">
        <nav class="navbar d-lg-none">
          <div class="container-fluid d-flex justify-content-between">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar"
              aria-expanded="false">
              <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
              {{ config('app.name', 'Laravel') }}
            </a>
          </div>
        </nav>

        <main class="container-fluid py-2">
          {{ $slot }}
        </main>
      </div>
    </div>
  </div>
</body>

</html>
