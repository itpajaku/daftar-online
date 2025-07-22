  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <h6 class="card-title mb-2">Total Permohonan</h6>
              <h2 class="mb-1 fw-bold">{{ $total }}</h2>
              {{-- <p class="mb-0 text-success small">
                <i class="bi bi-arrow-up me-1"></i>12.5% increase
              </p> --}}
            </div>
            <div class="p-2 bg-primary bg-opacity-10 rounded-3">
              <i class="bi bi-people fs-5 text-primary"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <h6 class="card-title mb-2">Sudah Diverifikasi</h6>
              <h2 class="mb-1 fw-bold">{{ $sudah_verifikasi }}</h2>
              {{-- <p class="mb-0 text-danger small">
                <i class="bi bi-arrow-down me-1"></i>2.1% decrease
              </p> --}}
            </div>
            <div class="p-2 bg-success bg-opacity-10 rounded-3">
              <i class="bi bi-currency-dollar fs-5 text-success"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <h6 class="card-title mb-2">Belum Diverifikasi</h6>
              <h2 class="mb-1 fw-bold">{{ $belum_verifikasi }}</h2>
              {{-- <p class="mb-0 text-success small">
                <i class="bi bi-arrow-up me-1"></i>5.7% increase
              </p> --}}
            </div>
            <div class="p-2 bg-warning bg-opacity-10 rounded-3">
              <i class="bi bi-cart fs-5 text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
