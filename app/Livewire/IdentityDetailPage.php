<?php

namespace App\Livewire;

use App\Events\IdentityRejectedEvent;
use App\Models\Identity;
use App\Service\HashRouteId;
use App\Traits\AlertElement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class IdentityDetailPage extends Component
{
    use AlertElement;

    public $identity;

    public $alasan_penolakan = '';

    public $selected_preset = '';

    public function mount(HashRouteId $hash_id)
    {
        $this->identity = Identity::find($hash_id->getDecodedId());
    }

    public function updatedSelectedPreset($value)
    {
        if (! empty($value) && $value !== 'custom') {
            $this->alasan_penolakan = $value;
        } elseif ($value === 'custom') {
            $this->alasan_penolakan = '';
        }
    }

    public function rejectApplication()
    {
        $this->validate([
            'alasan_penolakan' => 'required|string|min:5|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan harus diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $this->identity->update([
                'status' => 'rejected',
                'alasan_penolakan' => $this->alasan_penolakan,
                'rejected_at' => now(),
            ]);

            DB::commit();

            event(new IdentityRejectedEvent($this->identity, $this->alasan_penolakan));

            $this->identity->refresh();
            $this->reset(['alasan_penolakan', 'selected_preset']);

            session()->flash('alert_success', $this->successAlertElement('Permohonan berhasil ditolak.'));
            $this->dispatch('close-reject-modal');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('alert_error', $this->errorAlertElement($th->getMessage()));
        }
    }

    public function cancelRejection()
    {
        try {
            DB::beginTransaction();

            $this->identity->update([
                'status' => 'pending',
                'alasan_penolakan' => null,
                'rejected_at' => null,
            ]);

            DB::commit();

            $this->identity->refresh();
            session()->flash('alert_success', $this->successAlertElement('Penolakan berhasil dibatalkan. Status permohonan kembali menjadi Belum Verifikasi.'));
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('alert_error', $this->errorAlertElement($th->getMessage()));
        }
    }

    public function render()
    {
        return view('livewire.identity-detail-page');
    }

    public function download_ktp()
    {
        try {
            $fullpath = Storage::disk('public')->path($this->identity->bank_account->file_ktp);
            $ext = pathinfo($fullpath, PATHINFO_EXTENSION);

            return response()->download(
                $fullpath,
                'KTP-'.Str::replace(' ', '-', $this->identity->nama_lengkap.'.'.$ext)
            );
        } catch (\Throwable $th) {
            session()->flash('alert_download_error', $this->errorAlertElement($th->getMessage()));
        }
    }

    public function delete()
    {
        try {
            DB::beginTransaction();
            $this->identity->delete();
            DB::commit();
            session()->flash('alert_success', 'Berhasil menghapus data identitas');

            return $this->redirect('/permohonan-akun');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('alert_error', $th->getMessage());
        }
    }
}
