<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Service\HashId;
use App\Models\Identity;
use App\Models\SensitiveIdentityKey;
use App\Service\HashRouteId;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.wizard')]
class IdentityStepWizard extends Component
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

    public $identity_id;

    public function render()
    {
        return view('livewire.identity-step-wizard');
    }

    public function mount(HashRouteId $hash_id, HashId $hash)
    {
        if ($hash_id->getDecodedId()) {
            $this->identity_id = $hash_id->getOriginalId();
            $identity = Identity::find($hash_id->getDecodedId());
            $this->nama_lengkap = $identity->nama_lengkap;
            $this->jenis_kelamin = $identity->jenis_kelamin;
            $this->tanggal_lahir = $identity->tanggal_lahir;
            $this->tempat_lahir = $identity->tempat_lahir;
            $this->nomor_kependudukan = Crypt::decryptString($identity->nomor_kependudukan);
            $this->nomor_telepon = "62" . $hash->decodeFirst($identity->nomor_telepon);
            $this->email = $identity->email;
            $this->pekerjaan = $identity->pekerjaan;
            $this->pendidikan = $identity->pendidikan;
            $this->status_perkawinan = $identity->status_perkawinan;
            $this->agama = $identity->agama;
            $this->alamat = $identity->alamat;
        }
    }

    public function save(HashId $hashId)
    {
        $this->validate();
        $hashed_nik = hash_hmac('sha256', $this->nomor_kependudukan, config('app.key'));
        $hashed_nomor_telepon = hash_hmac('sha256', $this->nomor_telepon, config('app.key'));

        try {
            if (!$this->identity_id) {
                if (SensitiveIdentityKey::where('hash_nik', $hashed_nik)->orWhere('hash_nomor_telepon', $hashed_nomor_telepon)->exists()) {
                    throw new \Exception("Nomor Kependudukan Atau Nomor Telepon Sudah Terdaftar", 1);
                }
            }

            DB::beginTransaction();
            $data = [
                ...$this->except(['nomor_kependudukan', 'nomor_telepon', 'identity_id']),
                'nomor_kependudukan' => Crypt::encryptString($this->nomor_kependudukan),
                'nomor_telepon' => Crypt::encryptString($this->nomor_telepon),
            ];

            if ($this->identity_id) {
                $identity = Identity::find($hashId->decodeFirst($this->identity_id));
                $identity->update($data);
                $identity->sensitive_identity_key()->update([
                    'hash_nik' => $hashed_nik,
                    'hash_nomor_telepon' => $hashed_nomor_telepon,
                ]);
            } else {
                $identity = Identity::create($data);
                $identity->sensitive_identity_key()->create([
                    'hash_nik' => $hashed_nik,
                    'hash_nomor_telepon' => $hashed_nomor_telepon,
                ]);
            }
            DB::commit();
            session()->flash('message', 'Data berhasil disimpan!');

            return $this->redirect("/step-2/{$hashId->encode($identity->id)}");
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan. ' . $th->getMessage());
        }
    }
}
