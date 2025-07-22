<?php

namespace App\Livewire;

use App\Models\Identity;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IdentityTotalCard extends Component
{
    public $total;
    public $belum_verifikasi;
    public $sudah_verifikasi;

    public function mount()
    {
        $this->total = Identity::count();
        $this->sudah_verifikasi = Identity::has('ecourt_account')->count();
        $this->belum_verifikasi = Identity::doesntHave('ecourt_account')->count();
    }

    public function render()
    {
        return view('livewire.identity-total-card');
    }
}
