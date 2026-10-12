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

  @if (session()->has('alert_success'))
    {!! session('alert_success') !!}
  @endif
  @if (session()->has('alert_error'))
    {!! session('alert_error') !!}
  @endif

  <div class="row">
    <div class="col-8">
      <div class="card">
        <div class="card-header text-bg-primary">
          <h5 class="mb-0 text-white">Form with view only</h5>
        </div>
        <form class="form-horizontal">
          <div class="form-body">
            <div class="card-body">
              <h5 class="card-title mb-0">Informasi Permohonan</h5>
            </div>
            <hr class="m-0" />
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Waktu Pengajuan :</label>
                    <div class="col-md-8">
                      <x-copy-text><p class="mb-0">{{ $identity->created_at ? $identity->created_at->translatedFormat('d F Y H:i:s') . ' (' . $identity->created_at->diffForHumans() . ')' : '-' }}</p></x-copy-text>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="form-label text-end col-md-4">Hubungi Pemohon :</label>
                    <div class="col-md-8">
                      @php
                        $waNumber = preg_replace('/[^0-9]/', '', $identity->nomor_telepon_original);
                        if(str_starts_with($waNumber, '0')) $waNumber = '62' . substr($waNumber, 1);
                      @endphp
                      @if($waNumber)
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-success btn-sm">
                          <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
                        </a>
                      @else
                        -
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="m-0" />
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
                      <x-copy-text><p class="mb-0">{{ $identity->tempat_lahir }}</p></x-copy-text>
                      <ul class="mt-1 mb-0 ps-3">
                        <li class="mb-1"><x-copy-text><p class="mb-0">{{ $identity->tanggal_lahir->translatedFormat('d F Y') }}</p></x-copy-text></li>
                        <li class="mb-1"><x-copy-text><p class="mb-0">{{ $identity->tanggal_lahir->format('d/m/Y') }}</p></x-copy-text></li>
                        <li class="mb-1"><x-copy-text><p class="mb-0">{{ $identity->tanggal_lahir->format('Y-m-d') }}</p></x-copy-text></li>
                      </ul>
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
                      <x-copy-text><p class="mb-0">{{ preg_replace('/^0/', '62', $identity->nomor_telepon_original) }}</p></x-copy-text>
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
      <!-- Card Status Permohonan -->
      <div class="card mb-3">
        <div class="card-header bg-light">
          <h5 class="mb-0 fw-semibold">Status Permohonan</h5>
        </div>
        <div class="card-body">
          @if ($identity->isVerified())
            <div class="alert alert-success d-flex align-items-center mb-0">
              <i class="bi bi-check-circle-fill me-2 fs-4 text-success"></i>
              <div>
                <strong class="d-block">Sudah Diverifikasi</strong>
                <span class="small">Akun E-Court telah berhasil diterbitkan.</span>
              </div>
            </div>
          @elseif ($identity->isRejected())
            <div class="alert alert-danger mb-2">
              <div class="d-flex align-items-center mb-1">
                <i class="bi bi-x-circle-fill me-2 fs-5 text-danger"></i>
                <strong>Permohonan Ditolak</strong>
              </div>
              <div class="small mb-2">
                <strong>Alasan:</strong>
                <div class="p-2 bg-white rounded border border-danger-subtle mt-1 text-dark">
                  {{ $identity->alasan_penolakan }}
                </div>
              </div>
              @if ($identity->rejected_at)
                <div class="text-muted small mb-2">
                  <i class="bi bi-clock me-1"></i>{{ $identity->rejected_at->translatedFormat('d F Y H:i') }}
                </div>
              @endif
              <button type="button" wire:click="cancelRejection" class="btn btn-outline-danger btn-sm w-100"
                onclick="if(!confirm('Batalkan penolakan permohonan ini? Status akan kembali menjadi Belum Verifikasi.')){return false;}">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan Penolakan
              </button>
            </div>
          @else
            <div class="alert alert-warning mb-2">
              <div class="d-flex align-items-center">
                <i class="bi bi-hourglass-split me-2 fs-5 text-warning"></i>
                <div>
                  <strong>Belum Diverifikasi</strong>
                  <div class="small">Menunggu verifikasi berkas dan penerbitan akun E-Court.</div>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
              <i class="bi bi-x-circle me-1"></i> Tolak Permohonan
            </button>
          @endif
        </div>
      </div>

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

  <!-- Modal Tolak Permohonan -->
  <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="rejectModalLabel"><i class="bi bi-x-circle me-2"></i>Tolak Permohonan Akun</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="rejectApplication">
          <div class="modal-body">
            <p class="text-muted small">
              Tentukan alasan penolakan permohonan untuk <strong>{{ $identity->nama_lengkap }}</strong>. Alasan ini akan ditampilkan pada halaman Timeline pemohon agar pemohon dapat memperbaiki data yang kurang.
            </p>

            <div class="mb-3">
              <label for="presetAlasan" class="form-label fw-semibold">Pilih Alasan Umum :</label>
              <select id="presetAlasan" wire:model.live="selected_preset" class="form-select">
                <option value="">-- Pilih template alasan penolakan --</option>
                <option value="Foto / Scan KTP buram, terpotong, atau tidak terbaca dengan jelas.">Foto / Scan KTP buram, terpotong, atau tidak terbaca</option>
                <option value="Alamat domisili / KTP tidak lengkap atau tidak jelas.">Alamat domisili / KTP tidak lengkap atau tidak jelas</option>
                <option value="Nama pemilik buku rekening tidak sesuai dengan nama pada KTP pemohon.">Nama pemilik buku rekening tidak sesuai KTP</option>
                <option value="Nomor KTP atau Nomor Telepon tidak valid.">Nomor KTP / Telepon tidak valid</option>
                <option value="custom">Lainnya (tulis alasan kustom)</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="alasan_penolakan" class="form-label fw-semibold">Detail Alasan Penolakan <span class="text-danger">*</span> :</label>
              <textarea id="alasan_penolakan" wire:model="alasan_penolakan" rows="4"
                class="form-control @error('alasan_penolakan') is-invalid @enderror"
                placeholder="Tuliskan alasan penolakan dan petunjuk perbaikan berkas..."></textarea>
              @error('alasan_penolakan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger" wire:loading.attr="disabled">
              <span wire:loading.remove wire:target="rejectApplication"><i class="bi bi-send me-1"></i> Konfirmasi Tolak</span>
              <span wire:loading wire:target="rejectApplication"><span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...</span>
            </button>
          </div>
        </form>
      </div>
    </div>
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

<script>
  document.addEventListener('livewire:initialized', () => {
    Livewire.on('close-reject-modal', () => {
      const modalEl = document.getElementById('rejectModal');
      if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
          modal.hide();
        }
      }
    });
  });
</script>
