<?php

namespace App\Livewire;

use App\Models\Identity;
use App\Service\HashRouteId;
use App\Traits\AlertElement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('components.layouts.app')]
class IdentityDetailPage extends Component
{
    use AlertElement;
    public $identity;

    public function mount(HashRouteId $hash_id)
    {
        $this->identity = Identity::find($hash_id->getDecodedId());
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
                "KTP-" . Str::replace(' ', '-', $this->identity->nama_lengkap . '.' . $ext)
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
