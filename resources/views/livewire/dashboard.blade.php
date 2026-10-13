<x-slot:sidebar>
  <x-layouts.sidebar />
</x-slot:sidebar>

<div class="container-fluid py-2">
  <!-- Header & Filter Bar -->
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-3 border-bottom">
    <div>
      <h3 class="fw-bold mb-1 text-primary">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard E-Court
      </h3>
      <p class="text-muted small mb-0">
        Pusat informasi dan pemantauan permohonan akun E-Court Pengguna Lain
      </p>
    </div>

    <div class="btn-toolbar mb-2 mb-md-0 d-flex gap-2 align-items-center">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-calendar-event text-muted"></i>
        </span>
        <select class="form-select form-select-sm border-start-0" wire:model.live="selectedMonth" title="Pilih Bulan">
          @for ($m = 1; $m <= 12; $m++)
            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
              {{ Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
            </option>
          @endfor
        </select>
        <select class="form-select form-select-sm" wire:model.live="selectedYear" title="Pilih Tahun">
          @for ($y = date('Y') - 4; $y <= date('Y'); $y++)
            <option value="{{ $y }}">{{ $y }}</option>
          @endfor
        </select>
      </div>

      <div class="btn-group">
        <a href="{{ route('permohonan-akun') }}" wire:navigate class="btn btn-sm btn-outline-primary">
          <i class="bi bi-list-check me-1"></i> Kelola Permohonan
        </a>
        <a href="{{ url('step-1') }}" wire:navigate class="btn btn-sm btn-primary">
          <i class="bi bi-plus-circle me-1"></i> Tambah
        </a>
      </div>
    </div>
  </div>

  <!-- KPI / Stats Cards -->
  <div class="row g-3 mb-4">
    <!-- Total Permohonan -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Permohonan</span>
              <h2 class="mt-2 mb-1 fw-bold text-dark">{{ number_format($this->stats['total']) }}</h2>
              <div class="d-flex align-items-center gap-1 small text-muted">
                <span class="badge bg-primary-subtle text-primary fw-medium px-2 py-1">
                  +{{ $this->stats['today'] }} hari ini
                </span>
                <span>• {{ $this->stats['this_month'] }} bulan ini</span>
              </div>
            </div>
            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
              <i class="bi bi-folder2-open fs-4"></i>
            </div>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
        </div>
      </div>
    </div>

    <!-- Menunggu Verifikasi -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Menunggu Verifikasi</span>
              <h2 class="mt-2 mb-1 fw-bold text-warning">{{ number_format($this->stats['pending']) }}</h2>
              <div class="d-flex align-items-center gap-1 small">
                @if($this->stats['pending'] > 0)
                  <span class="badge bg-warning text-dark fw-medium px-2 py-1">
                    <i class="bi bi-clock-history me-1"></i>Perlu Tindakan
                  </span>
                @else
                  <span class="badge bg-success-subtle text-success fw-medium px-2 py-1">
                    <i class="bi bi-check-all me-1"></i>Semua Beres
                  </span>
                @endif
              </div>
            </div>
            <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
              <i class="bi bi-hourglass-split fs-4"></i>
            </div>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-warning" role="progressbar"
               style="width: {{ $this->stats['total'] > 0 ? ($this->stats['pending'] / $this->stats['total']) * 100 : 0 }}%">
          </div>
        </div>
      </div>
    </div>

    <!-- Terverifikasi -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Akun Diterbitkan</span>
              <h2 class="mt-2 mb-1 fw-bold text-success">{{ number_format($this->stats['verified']) }}</h2>
              <div class="d-flex align-items-center gap-1 small text-muted">
                <span class="badge bg-success-subtle text-success fw-medium px-2 py-1">
                  {{ $this->stats['verification_rate'] }}% rasio selesai
                </span>
              </div>
            </div>
            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
              <i class="bi bi-patch-check-fill fs-4"></i>
            </div>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-success" role="progressbar"
               style="width: {{ $this->stats['total'] > 0 ? ($this->stats['verified'] / $this->stats['total']) * 100 : 0 }}%">
          </div>
        </div>
      </div>
    </div>

    <!-- Ditolak -->
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div>
              <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Permohonan Ditolak</span>
              <h2 class="mt-2 mb-1 fw-bold text-danger">{{ number_format($this->stats['rejected']) }}</h2>
              <div class="d-flex align-items-center gap-1 small text-muted">
                <span class="badge bg-danger-subtle text-danger fw-medium px-2 py-1">
                  Berkas tidak sesuai
                </span>
              </div>
            </div>
            <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3">
              <i class="bi bi-x-circle-fill fs-4"></i>
            </div>
          </div>
        </div>
        <div class="progress" style="height: 4px;">
          <div class="progress-bar bg-danger" role="progressbar"
               style="width: {{ $this->stats['total'] > 0 ? ($this->stats['rejected'] / $this->stats['total']) * 100 : 0 }}%">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row g-3 mb-4">
    <!-- Area Chart: Tren Pendaftaran Harian -->
    <div class="col-12 col-xl-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="fw-semibold mb-0 text-dark">Statistik Permohonan Harian</h5>
            <small class="text-muted">Tren pendaftaran pemohon per hari pada periode yang dipilih</small>
          </div>
          <span class="badge bg-light text-dark border">
            Bulan: {{ Carbon\Carbon::create(null, (int)$selectedMonth, 1)->translatedFormat('F') }} {{ $selectedYear }}
          </span>
        </div>
        <div class="card-body">
          <div id="chart-permohonan" style="min-height: 330px;"></div>
        </div>
      </div>
    </div>

    <!-- Donut Chart: Distribusi Status -->
    <div class="col-12 col-xl-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h5 class="fw-semibold mb-0 text-dark">Distribusi Status</h5>
          <small class="text-muted">Komposisi seluruh permohonan</small>
        </div>
        <div class="card-body d-flex flex-column justify-content-center">
          <div id="chart-status-donut" style="min-height: 270px;"></div>
          <div class="mt-3 pt-3 border-top">
            <div class="row text-center g-2">
              <div class="col-4">
                <small class="text-muted d-block">Pending</small>
                <span class="fw-bold text-warning">{{ $this->stats['pending'] }}</span>
              </div>
              <div class="col-4">
                <small class="text-muted d-block">Selesai</small>
                <span class="fw-bold text-success">{{ $this->stats['verified'] }}</span>
              </div>
              <div class="col-4">
                <small class="text-muted d-block">Ditolak</small>
                <span class="fw-bold text-danger">{{ $this->stats['rejected'] }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom Section: Antrean Pending & Snapshot Webhook / Quick Actions -->
  <div class="row g-3">
    <!-- Antrean Permohonan Terbaru (Menunggu Tindakan) -->
    <div class="col-12 col-xl-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="fw-semibold mb-0 text-dark">
              <i class="bi bi-clock-history text-warning me-1"></i> Antrean Permohonan Terbaru
            </h5>
            <small class="text-muted">Permohonan yang membutuhkan verifikasi berkas dan penerbitan akun</small>
          </div>
          <a href="{{ route('permohonan-akun') }}" wire:navigate class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Pemohon</th>
                  <th>Telepon / WA</th>
                  <th>Bank / Rekening</th>
                  <th>Waktu Pengajuan</th>
                  <th class="text-end pe-3">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($this->recentPendingIdentities as $item)
                  <tr>
                    <td class="ps-3">
                      <div class="fw-bold text-dark">{{ $item->nama_lengkap }}</div>
                      <small class="text-muted">{{ $item->email }}</small>
                    </td>
                    <td>
                      <div class="small fw-semibold">{{ $item->nomor_telepon_original }}</div>
                      @php
                        $waNum = preg_replace('/[^0-9]/', '', $item->nomor_telepon_original);
                        if(str_starts_with($waNum, '0')) $waNum = '62' . substr($waNum, 1);
                      @endphp
                      @if($waNum)
                        <a href="https://wa.me/{{ $waNum }}" target="_blank" class="badge bg-success-subtle text-success text-decoration-none">
                          <i class="bi bi-whatsapp"></i> Chat
                        </a>
                      @endif
                    </td>
                    <td>
                      @if($item->bank_account)
                        <div class="small fw-semibold">{{ $item->bank_account->nama_bank }}</div>
                        <small class="text-muted">{{ $item->bank_account->nomor_rekening }}</small>
                      @else
                        <span class="badge bg-secondary-subtle text-secondary">Belum isi bank</span>
                      @endif
                    </td>
                    <td>
                      <div class="small text-dark">{{ $item->created_at?->diffForHumans() }}</div>
                      <small class="text-muted">{{ $item->created_at?->format('d/m/Y H:i') }}</small>
                    </td>
                    <td class="text-end pe-3">
                      <a href="{{ url('/identity', ['id' => $hashId->encode($item->id)]) }}"
                         wire:navigate
                         class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Verifikasi
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                      <i class="bi bi-check2-circle fs-1 text-success d-block mb-2"></i>
                      Tidak ada antrean permohonan yang menunggu verifikasi saat ini.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Shortcuts & System Notification Summary -->
    <div class="col-12 col-xl-4">
      <div class="d-flex flex-column gap-3 h-100">
        <!-- Quick Action Card -->
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white border-0 pt-3 pb-1">
            <h6 class="fw-bold mb-0 text-dark">
              <i class="bi bi-lightning-charge-fill text-primary me-1"></i> Tindakan Cepat
            </h6>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <a href="{{ url('step-1') }}" wire:navigate class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2">
                <span><i class="bi bi-person-plus text-primary me-2"></i> Input Permohonan Baru</span>
                <i class="bi bi-chevron-right text-muted small"></i>
              </a>
              <a href="{{ route('permohonan-akun') }}" wire:navigate class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2">
                <span><i class="bi bi-table text-success me-2"></i> Semua Data Permohonan</span>
                <i class="bi bi-chevron-right text-muted small"></i>
              </a>
              <a href="{{ route('settings.admin_ecourt') }}" wire:navigate class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2">
                <span><i class="bi bi-sliders text-warning me-2"></i> Pengaturan Satker & Kontak WA</span>
                <i class="bi bi-chevron-right text-muted small"></i>
              </a>
            </div>
          </div>
        </div>

        <!-- Webhook & Notifikasi Activity Card -->
        <div class="card border-0 shadow-sm flex-grow-1">
          <div class="card-header bg-white border-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
            <div>
              <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-broadcast text-info me-1"></i> Notifikasi & Webhook
              </h6>
              <small class="text-muted">Aktivitas pengiriman pesan/notifikasi</small>
            </div>
            <a href="{{ route('webhook.logs') }}" wire:navigate class="btn btn-sm btn-link p-0 text-decoration-none">
              Log <i class="bi bi-arrow-right"></i>
            </a>
          </div>
          <div class="card-body pt-0">
            <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
              <div class="text-center flex-fill border-end">
                <small class="text-muted d-block">Terkirim</small>
                <span class="fw-bold text-success">{{ $this->webhookSummary['success'] }}</span>
              </div>
              <div class="text-center flex-fill">
                <small class="text-muted d-block">Gagal</small>
                <span class="fw-bold text-danger">{{ $this->webhookSummary['failed'] }}</span>
              </div>
            </div>

            <div class="small fw-semibold text-muted mb-2">Riwayat Notifikasi Terkini:</div>
            <ul class="list-group list-group-flush small">
              @forelse($this->webhookSummary['recent'] as $log)
                <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center border-bottom-dashed">
                  <div class="text-truncate me-2" style="max-width: 180px;">
                    <span class="badge {{ $log->success ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} me-1">
                      {{ $log->success ? 'OK' : 'ERR' }}
                    </span>
                    <span class="fw-medium text-dark">{{ class_basename($log->event) }}</span>
                  </div>
                  <span class="text-muted" style="font-size: 0.75rem;">
                    {{ $log->created_at?->diffForHumans() }}
                  </span>
                </li>
              @empty
                <li class="list-group-item px-0 py-3 text-center text-muted">
                  Belum ada log pengiriman.
                </li>
              @endforelse
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ApexCharts Script -->
  @assets
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  @endassets

  @script
  <script>
    const chartData = @json($this->chartData);
    const statusData = @json($this->statusDistribution);

    // 1. Line/Area Chart: Tren Permohonan
    const areaOptions = {
      series: [{
        name: 'Permohonan Masuk',
        data: chartData.series
      }],
      chart: {
        type: 'area',
        height: 330,
        toolbar: { show: false },
        zoom: { enabled: false },
        fontFamily: 'inherit'
      },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 2.5 },
      xaxis: {
        categories: chartData.labels,
        title: { text: 'Tanggal' },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: {
        title: { text: 'Jumlah Permohonan' },
        min: 0,
        forceNiceScale: true,
        labels: {
          formatter: function(val) { return Math.floor(val); }
        }
      },
      tooltip: {
        y: { formatter: function (val) { return val + " permohonan"; } }
      },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.45,
          opacityTo: 0.05,
          stops: [0, 95, 100]
        }
      },
      colors: ['#0d6efd'],
      grid: {
        borderColor: '#f1f1f1',
        strokeDashArray: 4
      }
    };

    let areaChart = new ApexCharts(document.querySelector("#chart-permohonan"), areaOptions);
    areaChart.render();

    // 2. Donut Chart: Distribusi Status
    const donutOptions = {
      series: statusData.series,
      labels: statusData.labels,
      chart: {
        type: 'donut',
        height: 270,
        fontFamily: 'inherit'
      },
      colors: ['#f59f00', '#2fb344', '#d63939'], // Warning (Pending), Success (Verified), Danger (Rejected)
      legend: {
        position: 'bottom',
        fontSize: '13px'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return Math.round(val) + "%";
        }
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + " permohonan";
          }
        }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '65%',
            labels: {
              show: true,
              total: {
                show: true,
                label: 'Total',
                formatter: function (w) {
                  return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                }
              }
            }
          }
        }
      }
    };

    let donutChart = new ApexCharts(document.querySelector("#chart-status-donut"), donutOptions);
    donutChart.render();

    // Livewire Event Updates
    $wire.on('update-chart', (event) => {
      const eventData = Array.isArray(event) ? event[0] : event;
      const newData = eventData.data || eventData;

      if (areaChart && newData) {
        areaChart.updateSeries([{
          data: newData.series
        }]);
        areaChart.updateOptions({
          xaxis: {
            categories: newData.labels
          }
        });
      }
    });
  </script>
  @endscript
</div>
