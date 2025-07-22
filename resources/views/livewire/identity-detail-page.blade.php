<x-slot:sidebar>
  <x-layouts.sidebar />
</x-slot:sidebar>

<div class="container-fluid">
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <div>
      <h3 class="fw-semibold">Detail Permohonan</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/permohonan-akun') }}">Daftar Permohonan</a></li>
          <li class="breadcrumb-item"><a href="#">Detail</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $identity->nama_lengkap }}</li>
        </ol>
      </nav>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
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

  <div class="row">
    <div class="col-8">
      <div class="card">
        <div class="card-header text-bg-primary">
          <h5 class="mb-0 text-white">Form with view only</h5>
        </div>
        <form class="form-horizontal">
          <div class="form-body">
            <div class="card-body">
              <h5 class="card-title mb-0">Person Info</h5>
            </div>
            <hr class="m-0" />
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Nama Lengkap :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->nama_lengkap }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Jenis kelamin :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->jenis_kelamin }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
              </div>
              <!--/row-->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Nomor KTP :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->nomor_kependudukan_original }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Date of Birth :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->tempat_lahir }}, {{ $identity->tanggal_lahir->format('j F Y') }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
              </div>
              <!--/row-->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Pekerjaan :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->pekerjaan }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Pendidikan :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->pendidikan }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
              </div>
              <!--/row-->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Agama :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->agama }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Alamat :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->alamat }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
              </div>
              {{-- Row --}}
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Nomor WA :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->nomor_telepon_original }}</p>
                    </div>
                  </div>
                </div>
                <!--/span-->
                {{-- <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Pendidikan :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->pendidikan }}</p>
                    </div>
                  </div>
                </div> --}}
                <!--/span-->
              </div>

            </div>
            <hr class="m-0" />
            <div class="card-body">
              <h5 class="card-title mb-0">Bank Account</h5>
              @if (session('alert_download_error'))
                {!! session('alert_download_error') !!}
              @endif
            </div>
            <hr class="m-0" />
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Nama Bank :</label>
                    <div class="col-md-8">
                      <p>
                        {{ $identity->bank_account->nama_bank }}
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Nomor Rekening :</label>
                    <div class="col-md-8">
                      <p>
                        {{ $identity->bank_account->nomor_rekening }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">

                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Nama Akun :</label>
                    <div class="col-md-8">
                      <p>
                        {{ $identity->bank_account->nama_akun }}
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">File KTP :</label>
                    <div class="col-md-8">
                      <button wire:click="download_ktp" class="btn btn-success btn-sm">
                        <i class="bi bi-download"></i>
                        Download KTP
                      </button>
                    </div>
                  </div>
                </div>
                <!--/span-->
              </div>
              <!--/row-->

            </div>
            <div class="form-actions border-top">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-primary">
                          <i class="bi bi-pencil-square "></i>
                          Edit
                        </button>
                        <button wire:click="delete" type="button" class="btn bg-danger-subtle text-danger ms-6"
                          wire:confirm="Apa anda yakin ? User tidak akan bisa melihat akun ecourt mereka jika data ini dihapus.">
                          <i class="bi bi-trash"></i>
                          Hapus Data
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6"></div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-4">
      <div class="card">
        <div class="card-header text-bg-primary">
          <h5 class="mb-0 text-white">Setting E-Court Account</h5>
        </div>
        <div class="px-4 py-3 border-bottom">
          <h4 class="card-title mb-0">Form E-Court</h4>
        </div>
        <div class="card-body">
          <livewire:form-e-court-account identity_id="{{ $identity->hashed_id }}" />
        </div>
      </div>
    </div>
  </div>
</div>
