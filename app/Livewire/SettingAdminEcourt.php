<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Traits\AlertElement;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class SettingAdminEcourt extends Component
{
    use AlertElement;

    #[Validate('required', message: 'Tidak boleh kosong')]
    public $nama_admin;

    #[Validate('required', message: 'Tidak boleh kosong')]
    public $nomor_admin;

    public function mount()
    {
        $this->nama_admin = app('settings')['admin_ecourt'];
        $this->nomor_admin = app('settings')['wa_admin_ecourt'];
    }

    public function render()
    {
        return view('livewire.setting-admin-ecourt');
    }

    public function update_data_admin()
    {
        $this->validate();
        try {
            Setting::set('admin_ecourt', $this->nama_admin);
            Setting::set('wa_admin_ecourt', $this->nomor_admin);
            $this->dispatch('settings-saved');
            session()->flash('alert_success', $this->successAlertElement('Berhasil Menyimpan Data'));
        } catch (\Throwable $th) {
            session()->flash('alert_error', $this->errorAlertElement($th->getMessage()));
        }
    }
}
