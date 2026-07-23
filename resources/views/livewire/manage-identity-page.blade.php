<x-slot:sidebar>
  <x-layouts.sidebar />
</x-slot:sidebar>

<div class="container-fluid">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h3 class="fw-semibold">Permohonan Pembuatan Akun E-Court</h3>
    <div class="btn-toolbar mb-2 mb-md-0">
      <a href="{{ url('step-1') }}" wire:navigate class="btn btn-sm btn-primary me-2">
        <i class="bi bi-plus-circle me-1"></i> Tambah Permohonan
      </a>
      <div class="btn-group me-2">
        <button type="button" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-share me-1"></i> Share
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-download me-1"></i> Export
        </button>
      </div>
    </div>
  </div>

  <livewire:identity-total-card />

  <div class="row g-3">
    <!-- Recent Activity -->
    <div class="col-12 col-lg-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
          <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Tabel Daftar Permohonan Akun E-Court</h5>
            <button class="btn btn-sm">View All</button>
          </div>
        </div>
        <div class="card-body">
          <div class="list-group list-group-flush">
            <div class="table-responsive">
              {!! session('alert_error') !!}
              @if (session()->has('alert_success'))
                {!! session('alert_success') !!}
              @endif
              <table class="table table-hover table-bordered text-nowrap">
                <thead class="text-center">
                  <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>NIK</th>
                    <th>Telepon</th>
                    <th>Pekerjaan</th>
                    <th>Pendidikan</th>
                    <th>Status</th>
                    <th>Agama</th>
                    <th>Alamat</th>
                    <th width="150px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($identities as $n => $i)
                    <tr>
                      <td>{{ ++$n }}</td>
                      <td>{{ $i->nama_lengkap }} <br> {{ $i->jenis_kelamin }}</td>
                      <td>{{ $i->tempat_lahir }} {{ $i->tanggal_lahir->format('j F Y') }}</td>
                      <td>{{ $i->nomor_kependudukan_original }}</td>
                      <td>{{ $i->nomor_telepon_original }}</td>
                      <td>{{ $i->pekerjaan }}</td>
                      <td>{{ $i->pendidikan }}</td>
                      <td>
                        @if ($i->ecourt_account)
                          <span class="badge bg-success">Sudah Verifikasi</span>
                        @else
                          <span class="badge bg-warning text-dark">Belum Verifikasi</span>
                        @endif
                      </td>
                      <td>{{ $i->agama }}</td>
                      <td>{{ Str::substr($i->alamat, 0, 20) }} ...</td>
                      <td class="text-center">
                        <a wire:navigate href="{{ url('/identity', ['id' => $hash->encode($i->id)]) }}"
                          class="btn btn-primary">
                          <i class="bi bi-eye"></i>
                          Detail
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            {{ $identities->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
