<?php

namespace App\Livewire;

use App\Models\Identity;
use App\Service\HashRouteId;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class IdentityDetailPage extends Component
{
    public $identity;

    public function mount(HashRouteId $hash_id)
    {
        $this->identity = Identity::find($hash_id->getDecodedId());
    }

    public function render()
    {
        return view('livewire.identity-detail-page');
    }
}
