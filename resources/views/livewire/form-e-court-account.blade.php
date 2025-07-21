<div>
  <form wire:submit="save">
    @if (session('alert_error'))
      {!! session('alert_error') !!}
    @endif
    @if (session('alert_success'))
      {!! session('alert_success') !!}
    @endif
    <div class="text-center" wire:loading>
      Mohon Tunggu ...
    </div>
    <div class="mb-4 row align-items-center">
      <label for="exampleInputText31" class="form-label col-sm-3 col-form-label">Username</label>
      <div class="col-sm-9">
        <div class="input-group border rounded-1">
          <span class="input-group-text bg-transparent px-6 border-0" id="basic-addon1">
            <i class="bi bi-person-fill fs-6"></i>
          </span>
          <input wire:model="ecourt_account.username" type="text"
            class="form-control ps-2 
            @error('ecourt_account.username') is-invalid @enderror"
            id="exampleInputText31" placeholder="Username E-Court">
          @error('ecourt_account.username')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
      </div>
    </div>
    <div class="mb-4 row align-items-center">
      <label for="exampleInputText32" class="form-label col-sm-3 col-form-label">Password</label>
      <div class="col-sm-9">
        <div class="input-group border rounded-1">
          <span class="input-group-text bg-transparent px-6 border-0" id="basic-addon1">
            <i class="bi bi-key-fill fs-6"></i>
          </span>
          <input wire:model="ecourt_account.password" type="text" id="exampleInputText32"
            class="form-control  ps-2
              @error('ecourt_account.password') is-invalid @enderror"
            placeholder="Password E-Court">
          @error('ecourt_account.password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-9">
        <button class="btn btn-primary">
          <i class="bi bi-save"></i>
          Simpan
        </button>
      </div>
    </div>
  </form>
</div>
