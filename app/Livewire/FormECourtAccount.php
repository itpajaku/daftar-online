<?php

namespace App\Livewire;

use App\Livewire\Forms\ECourtAccountForm;
use App\Models\EcourtAccount;
use App\Service\HashId;
use App\Traits\AlertElement;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FormECourtAccount extends Component
{
    use AlertElement;

    public ECourtAccountForm $ecourt_account;
    public $identity_id;

    public function mount($identity_id = null, HashId $hash)
    {
        $originalId = $hash->decodeFirst($identity_id);
        $ecourtAccount =  EcourtAccount::firstOrNew(['identity_id' => $originalId]);
        $this->ecourt_account->username = $ecourtAccount->username;
        $this->ecourt_account->password = $ecourtAccount->password;
        $this->identity_id = $originalId;
    }

    public function render()
    {
        return view('livewire.form-e-court-account');
    }

    public function save()
    {
        $validated = $this->ecourt_account->validate();
        try {
            DB::beginTransaction();

            EcourtAccount::updateOrCreate(['identity_id' => $this->identity_id], $validated);

            session()->flash('alert_success', $this->successAlertElement('User dan Password berhasil disimpan'));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('alert_error', $this->errorAlertElement($th->getMessage()));
        }
    }
}
