<x-slot:sidebar>
  <x-layouts.sidebar />
</x-slot:sidebar>

<div class="container-fluid">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h3 class="fw-semibold">Dashboard</h3>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group me-2">
        <button type="button" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-share me-1"></i> Share
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-download me-1"></i> Export
        </button>
      </div>
      <div class="d-flex gap-2">
        <select class="form-select form-select-sm" wire:model.live="selectedMonth">
          @for ($m = 1; $m <= 12; $m++)
            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
          @endfor
        </select>
        <select class="form-select form-select-sm" wire:model.live="selectedYear">
          @for ($y = date('Y') - 5; $y <= date('Y'); $y++)
            <option value="{{ $y }}">{{ $y }}</option>
          @endfor
        </select>
      </div>
    </div>
  </div>

  <!-- Chart Container -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-0 pt-4 pb-0">
      <h5 class="mb-0">Statistik Permohonan Harian</h5>
    </div>
    <div class="card-body">
      <div id="chart-permohonan" style="min-height: 350px;"></div>
    </div>
  </div>

  <!-- ApexCharts CDN -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  
  @script
  <script>
    const chartData = @json($this->chartData);
    
    const options = {
      series: [{
        name: 'Permohonan',
        data: chartData.series
      }],
      chart: {
        type: 'area',
        height: 350,
        toolbar: { show: false },
        zoom: { enabled: false }
      },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 2 },
      xaxis: {
        categories: chartData.labels,
        title: { text: 'Tanggal' }
      },
      yaxis: {
        title: { text: 'Jumlah' },
        min: 0,
        forceNiceScale: true,
        labels: {
            formatter: function(val) { return Math.floor(val) }
        }
      },
      tooltip: {
        y: { formatter: function (val) { return val + " permohonan" } }
      },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.7,
          opacityTo: 0.1,
          stops: [0, 90, 100]
        }
      },
      colors: ['#0d6efd']
    };

    let chart = new ApexCharts(document.querySelector("#chart-permohonan"), options);
    chart.render();

    $wire.on('update-chart', (event) => {
        // Livewire v3 passes arguments as an array
        const eventData = Array.isArray(event) ? event[0] : event;
        const newData = eventData.data || eventData;
        
        chart.updateSeries([{
            data: newData.series
        }]);
        chart.updateOptions({
            xaxis: {
                categories: newData.labels
            }
        });
    });
  </script>
  @endscript
</div>
