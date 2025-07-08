<?php

namespace App\Livewire;

use App\Service\HashId;
use App\Models\Identity;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.wizard')]
class WelcomeStepWizard extends Component
{
    #[Validate('required', message: 'Nama lengkap tidak boleh kosong.')]
    #[Validate('max:255', message: 'Maksimal 255 karakter.')]
    public $nama_lengkap;

    #[Validate(rule: 'required', message: 'Jenis kelamin tidak boleh kosong.',)]
    public $jenis_kelamin;

    #[Validate(rule: 'date', message: 'Harus berupa tanggal yang valid.', attribute: 'tanggal_lahir')]
    #[Validate(rule: 'required', message: 'Tanggal lahir tidak boleh kosong dan harus berupa tanggal yang valid.', attribute: 'tanggal_lahir')]
    public $tanggal_lahir;

    #[Validate(rule: 'required', message: 'Tempat lahir tidak boleh kosong.',)]
    #[Validate(rule: 'max:255', message: 'Maksimal 255 karakter.',)]
    public $tempat_lahir;

    #[Validate(rule: 'numeric', message: 'Harus berupa nomor.',)]
    #[Validate(rule: 'digits_between:1,20', message: 'Maksimal 20 Digit.',)]
    #[Validate(rule: 'required', message: 'Nomor kependudukan tidak boleh kosong.',)]
    public $nomor_kependudukan;

    #[Validate(rule: 'required', message: 'Nomor telepon tidak boleh kosong.',)]
    #[Validate(rule: 'numeric', message: 'harus berupa nomor.',)]
    #[Validate(rule: 'digits_between:1,20', message: 'Maksimal 20 karakter.',)]
    public $nomor_telepon;

    #[Validate(rule: 'required', message: 'Email tidak boleh kosong.',)]
    #[Validate(rule: 'email', message: 'Harus berupa email yang valid.',)]
    #[Validate(rule: 'max:255', message: 'Maksimal 255 karakter.',)]
    public $email;

    #[Validate(rule: 'required', message: 'Pekerjaan tidak boleh kosong.',)]
    #[Validate(rule: 'max:255', message: 'Maksimal 255 karakter.',)]
    public $pekerjaan;

    #[Validate(rule: 'required', message: 'Pendidikan terakhir tidak boleh kosong.',)]
    #[Validate(rule: 'max:255', message: 'Maksimal 255 karakter.',)]
    public $pendidikan;

    #[Validate(rule: 'required', message: 'Status perkawinan tidak boleh kosong.',)]
    #[Validate(rule: 'max:255', message: 'Maksimal 255 karakter.',)]
    public $status_perkawinan;

    #[Validate(rule: 'required', message: 'Agama tidak boleh kosong.',)]
    #[Validate(rule: 'max:255', message: 'Maksimal 255 karakter.',)]
    public $agama;

    #[Validate(rule: 'required', message: 'Alamat tidak boleh kosong.',)]
    #[Validate(rule: 'max:512', message: 'Maksimal 512 karakter.',)]
    public $alamat;

    public function render()
    {
        return view('livewire.welcome-step-wizard');
    }

    public function save(HashId $hashId)
    {
        $this->validate();
        try {
            $identity = Identity::create([
                ...$this->except(['nomor_kependudukan', 'nomor_telepon']),
                'nomor_kependudukan' => Crypt::encryptString($this->nomor_kependudukan),
                'nomor_telepon' => $hashId->encode($this->nomor_telepon),
            ]);

            session()->flash('message', 'Data berhasil disimpan!');
            $id = $hashId->encode($identity->id);

            return $this->redirect("step-2/{$id}");
        } catch (\Throwable $th) {
            session()->flash('error', 'Terjadi kesalahan.' . $th->getMessage());
        }
    }
}
