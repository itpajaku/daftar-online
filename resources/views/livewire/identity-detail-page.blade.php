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
                      <x-copy-text><p class="mb-0">{{ $identity->nama_lengkap }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Jenis kelamin :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">{{ $identity->jenis_kelamin }}</p></x-copy-text>
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
                      <x-copy-text><p class="mb-0">{{ $identity->nomor_kependudukan_original }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Date of Birth :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">{{ $identity->tempat_lahir }}, {{ $identity->tanggal_lahir->format('j F Y') }}
                        ({{ $identity->tanggal_lahir->format('d/m/Y') }})</p></x-copy-text>
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
                      <x-copy-text><p class="mb-0">{{ $identity->pekerjaan }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Pendidikan :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">{{ $identity->pendidikan }}</p></x-copy-text>
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
                      <x-copy-text><p class="mb-0">{{ $identity->agama }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Alamat :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">{{ $identity->alamat }}</p></x-copy-text>
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
                      <x-copy-text><p class="mb-0">{{ $identity->nomor_telepon_original }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <!--/span-->
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Email :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">{{ $identity->email }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <!--/span-->
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Status Nikah :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">{{ $identity->status_perkawinan }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <!--/span-->
                {{-- <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Email :</label>
                    <div class="col-md-8">
                      <p>{{ $identity->email }}</p>
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
                      <x-copy-text><p class="mb-0">
                        {{ $identity->bank_account ? $identity->bank_account->nama_bank : '-' }}
                      </p></x-copy-text>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Nomor Rekening :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">
                        {{ $identity->bank_account ? $identity->bank_account->nomor_rekening : '-' }}
                      </p></x-copy-text>
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
                      <x-copy-text><p class="mb-0">
                        {{ $identity->bank_account ? $identity->bank_account->nama_akun : '-' }}
                      </p></x-copy-text>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">File KTP :</label>
                    <div class="col-md-8 d-flex gap-2">
                      <button type="button" wire:click="download_ktp" class="btn btn-success btn-sm"
                        @if (!($identity->bank_account && $identity->bank_account->file_ktp)) disabled @endif>
                        <i class="bi bi-download"></i>
                        Download KTP
                      </button>
                      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewKtpModal"
                        @if (!($identity->bank_account && $identity->bank_account->file_ktp)) disabled @endif>
                        <i class="bi bi-eye"></i>
                        Lihat
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
                        <a href="{{ route('identity.edit', ['hash_id' => $identity->hashed_id]) }}"
                          class="btn btn-primary">
                          <i class="bi bi-pencil-square "></i>
                          Edit
                        </a>
                        <button wire:click="delete" type="button" class="btn btn-danger ms-6"
                          onclick="if(!confirm('Apa anda yakin ? User tidak akan bisa melihat akun ecourt mereka jika data ini dihapus.')){return false;}">
                          <i class="bi bi-trash me-1"></i>
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
  <!-- Modal View KTP -->
  <div class="modal fade" id="viewKtpModal" tabindex="-1" aria-labelledby="viewKtpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewKtpModalLabel">Lihat Dokumen KTP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          @if ($identity->bank_account && $identity->bank_account->file_ktp)
            <iframe src="{{ asset('storage/' . $identity->bank_account->file_ktp) }}" width="100%" height="600px" style="border: none; display: block;"></iframe>
          @else
            <div class="p-4 text-center text-muted">File KTP tidak tersedia.</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
