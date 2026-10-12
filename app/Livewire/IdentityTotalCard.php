<?php

namespace App\Livewire;

use App\Models\Identity;
use Livewire\Component;

class IdentityTotalCard extends Component
{
    public $total;

    public $belum_verifikasi;

    public $sudah_verifikasi;

    public $ditolak;

    public function mount()
    {
        $this->total = Identity::count();
        $this->sudah_verifikasi = Identity::where('status', 'verified')->orWhereHas('ecourt_account')->count();
        $this->ditolak = Identity::where('status', 'rejected')->count();
        $this->belum_verifikasi = Identity::where(function ($q) {
            $q->where('status', 'pending')->orWhereNull('status');
        })->doesntHave('ecourt_account')->count();
    }

    public function render()
    {
        return view('livewire.identity-total-card');
    }
}
