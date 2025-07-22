<x-layouts.settings>
  <div class="card shadow-sm">
    <div class="card-body">
      {!! session('alert_success') !!}
      {!! session('alert_error') !!}
      <h5 class="card-title">Update Data Admin Ecourt</h5>
      <p class="text-muted">Sesuaikan Dengan Satker Anda.</p>

      <form wire:submit.prevent="update_data_admin" class="mt-4">
        <div class="mb-3">
          <label for="nama_admin" class="form-label">Nama Admin</label>
          <input wire:model="nama_admin" type="text" class="form-control" id="nama_admin" required>
          @error('nama_admin')
            <div class="text-danger small">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="nomor_admin" class="form-label">Nomor WA Admin</label>
          <input wire:model="nomor_admin" type="text" class="form-control" id="nomor_admin" required>
          @error('nomor_admin')
            <div class="text-danger small">{{ $message }}</div>
          @enderror
        </div>

        <div>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-check-lg me-1"></i> Update Data
          </button>
        </div>
      </form>
    </div>
  </div>
</x-layouts.settings>
