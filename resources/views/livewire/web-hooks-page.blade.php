<x-slot:sidebar>
  <x-layouts.sidebar />
</x-slot:sidebar>
<div class="container">
  <h2 class="mb-4">Web Hook Management</h2>
  <hr>
  <div class="mb-3">
    <button class="btn btn-primary mb-2" wire:click="showAddModal">
      <i class="bi bi-plus"></i> Tambah Web Hook
    </button>

  </div>


  <!-- Modal -->
  @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.3);">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ $editId ? 'Edit Web Hook' : 'Tambah Web Hook' }}</h5>
            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
          </div>
          <form wire:submit.prevent="{{ $editId ? 'updateWebhook' : 'saveWebhook' }}">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">URL</label>
                <input type="text" class="form-control @error('url') is-invalid @enderror" wire:model.defer="url">
                @error('url')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Event</label>
                <select class="form-select @error('event') is-invalid @enderror" wire:model.defer="event">
                  <option value="">Pilih Event</option>
                  @foreach ($eventOptions as $eventClass)
                    <option value="{{ $eventClass }}">{{ class_basename($eventClass) }}</option>
                  @endforeach
                </select>
                @error('event')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Type</label>
                <select class="form-select @error('type') is-invalid @enderror" wire:model.defer="type">
                  <option value="POST">POST</option>
                  <option value="GET">GET</option>
                </select>
                @error('type')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Body</label>
                <textarea class="form-control @error('body') is-invalid @enderror" wire:model.defer="body" rows="3"></textarea>
                @error('body')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" wire:model.defer="is_active" id="is_active"
                  @if ($is_active) {{ 'checked' }} @endif>
                <label class="form-check-label" for="is_active">Aktif</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
              <button type="submit" class="btn btn-primary">{{ $editId ? 'Update' : 'Simpan' }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
  <div wire:loading wire:target="testWebhook" class="alert alert-warning mt-3">
    <strong>Webhook sedang diuji...</strong>
    <span class="spinner-border spinner-border-sm ms-2"></span>
  </div>
  @if ($testResult ?? false)
    <div class="alert alert-info mt-3">
      <strong>Test Result:</strong>
      <pre class="mb-0">{{ $testResult }}</pre>
    </div>
  @endif
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Nama</th>
        <th>URL</th>
        <th>Event</th>
        <th>Body</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($webhooks as $webhook)
        <tr>
          <td>{{ $webhook->name }}</td>
          <td>{{ $webhook->url }}</td>
          <td>{{ $webhook->event }}</td>
          <td><code>{{ $webhook->body }}</code></td>
          <td>
            @if ($webhook->is_active)
              <span class="badge bg-success">Aktif</span>
            @else
              <span class="badge bg-secondary">Nonaktif</span>
            @endif
          </td>
          <td>
            <div class="d-flex flex-row">
              <button class="btn btn-sm btn-warning" wire:click="editWebhook({{ $webhook->id }})">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-info" wire:click="testWebhook({{ $webhook->id }})">
                <i class="bi bi-play-circle"></i>
              </button>
              <button class="btn btn-sm btn-danger" wire:click="deleteWebhook({{ $webhook->id }})"
                wire:confirm="Hapus webhook ini?">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center">Belum ada webhook</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
