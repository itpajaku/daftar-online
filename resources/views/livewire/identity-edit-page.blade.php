<x-slot:sidebar>
  <x-layouts.sidebar />
</x-slot:sidebar>
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <i class="bi bi-person-fill"></i>
          Edit Identity
        </div>
        <div class="card-body">
          <form wire:submit.prevent="updateIdentity">
            @if (session()->has('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Nama Lengkap</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="nama_lengkap" class="form-control" />
                @error('nama_lengkap')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
              <div class="col-sm-9">
                <select wire:model.defer="jenis_kelamin" class="form-control">
                  <option value="">Pilih Jenis Kelamin</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
                @error('jenis_kelamin')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Tempat Lahir</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="tempat_lahir" class="form-control" />
                @error('tempat_lahir')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
              <div class="col-sm-9">
                <input type="date" wire:model.defer="tanggal_lahir" class="form-control" />
                @error('tanggal_lahir')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Nomor Kependudukan</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="nomor_kependudukan" class="form-control" />
                @error('nomor_kependudukan')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Nomor Telepon</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="nomor_telepon" class="form-control" />
                @error('nomor_telepon')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Email</label>
              <div class="col-sm-9">
                <input type="email" wire:model.defer="email" class="form-control" />
                @error('email')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Pekerjaan</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="pekerjaan" class="form-control" />
                @error('pekerjaan')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Pendidikan</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="pendidikan" class="form-control" />
                @error('pendidikan')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Status Perkawinan</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="status_perkawinan" class="form-control" />
                @error('status_perkawinan')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Agama</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="agama" class="form-control" />
                @error('agama')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Alamat</label>
              <div class="col-sm-9">
                <textarea wire:model.defer="alamat" class="form-control" rows="3"></textarea>
                @error('alamat')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Kewarganegaraan</label>
              <div class="col-sm-9">
                <input type="text" wire:model.defer="kewarganegaraan" class="form-control" />
                @error('kewarganegaraan')
                  <span class="error">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row">
              <div class="offset-sm-3 col-sm-9 d-flex gap-2">
                <button type="submit" class="btn btn-primary mt-3">
                  <i class="bi bi-save me-1"></i> Update Identity
                </button>
                <a href="{{ route('permohonan-akun') }}" class="btn btn-danger mt-3">
                  <i class="bi bi-x-circle me-1"></i> Cancel
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
