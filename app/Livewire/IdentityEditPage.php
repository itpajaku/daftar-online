<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Identity;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Validate;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class IdentityEditPage extends Component
{
    public $identity;
    public $nama_lengkap;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $nomor_kependudukan;
    public $nomor_telepon;
    public $email;
    public $pekerjaan;
    public $pendidikan;
    public $status_perkawinan;
    public $agama;
    public $alamat;
    public $kewarganegaraan;

    public function mount(\App\Service\HashRouteId $hash_id)
    {
        $this->identity = \App\Models\Identity::findOrFail($hash_id->getDecodedId());
        $this->nama_lengkap = $this->identity->nama_lengkap;
        $this->jenis_kelamin = $this->identity->jenis_kelamin;
        $this->tempat_lahir = $this->identity->tempat_lahir;
        $this->tanggal_lahir = optional($this->identity->tanggal_lahir)->format('Y-m-d');
        $this->nomor_kependudukan = $this->identity->nomor_kependudukan_original ?? '';
        $this->nomor_telepon = $this->identity->nomor_telepon_original ?? '';
        $this->email = $this->identity->email;
        $this->pekerjaan = $this->identity->pekerjaan;
        $this->pendidikan = $this->identity->pendidikan;
        $this->status_perkawinan = $this->identity->status_perkawinan;
        $this->agama = $this->identity->agama;
        $this->alamat = $this->identity->alamat;
        $this->kewarganegaraan = $this->identity->kewarganegaraan;
    }

    public function rules()
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nomor_kependudukan' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:255',
            'email' => 'required|email|unique:identities,email,' . $this->identity->id,
            // nomor_telepon uniqueness checked in sensitive_identity_keys
            'pekerjaan' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'agama' => 'required|string|max:255',
            'alamat' => 'required|string|max:512',
            'kewarganegaraan' => 'required|string|max:255',
        ];
    }

    public function updateIdentity()
    {
        $this->validate();

        // Check nomor_telepon uniqueness in sensitive_identity_keys
        $hashedNomorTelepon = hash('sha256', $this->nomor_telepon);
        $hashedNik = hash('sha256', $this->nomor_kependudukan);
        $existingKey = \App\Models\SensitiveIdentityKey::where('hash_nomor_telepon', $hashedNomorTelepon)
            ->where('identities_id', '!=', $this->identity->id)
            ->whereHas('identity', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->first();
        if ($existingKey) {
            $this->addError('nomor_telepon', 'Nomor telepon sudah digunakan oleh identitas lain.');
            return;
        }

        // Update identity record
        $this->identity->update([
            'nama_lengkap' => $this->nama_lengkap,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'nomor_kependudukan' => \Illuminate\Support\Facades\Crypt::encryptString($this->nomor_kependudukan),
            'nomor_telepon' => \Illuminate\Support\Facades\Crypt::encryptString($this->nomor_telepon),
            'email' => $this->email,
            'pekerjaan' => $this->pekerjaan,
            'pendidikan' => $this->pendidikan,
            'status_perkawinan' => $this->status_perkawinan,
            'agama' => $this->agama,
            'alamat' => $this->alamat,
            'kewarganegaraan' => $this->kewarganegaraan,
        ]);

        // Update or create sensitive_identity_keys
        \App\Models\SensitiveIdentityKey::updateOrCreate(
            ['identities_id' => $this->identity->id],
            [
                'hash_nik' => $hashedNik,
                'hash_nomor_telepon' => $hashedNomorTelepon,
            ]
        );

        session()->flash('success', 'Identity updated successfully!');
    }

    public function render()
    {
        return view('livewire.identity-edit-page');
    }
}
