<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Laravel') }}</title>
  @vite(['resources/css/modern.style.css', 'resources/css/timeline.css', 'resources/js/app.js'])
  @stack('styles')
  <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
  <link rel="manifest" href="/favicon/site.webmanifest">
</head>

<body>
  <!--  Body Wrapper -->

  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center my-4">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-4">
            <div class="card mb-0">
              <a href="javascript:void(0)" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="/logo.png" alt="">
              </a>
              <div class="card-body wizard-content">
                <h4 class="card-title mb-3">E- Register {{ ucwords(strtolower($sys_conf['NamaPN'])) }}</h4>
                <h6 class="card-subtitle mb-3 text-muted">Memudahkan pendaftaran perkara secara online. Untuk
                  melanjutkan, Silahkan
                  ikuti panduan
                  dibawah.
                </h6>
                <hr>
                {{ $slot }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @stack('scripts')
</body>

</html>
