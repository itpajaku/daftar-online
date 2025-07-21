<?php

namespace App\Livewire;

use App\Models\Identity;
use App\Service\HashId;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class ManageIdentityPage extends Component
{
    use WithPagination;

    public function render(HashId $hash)
    {
        return view('livewire.manage-identity-page', [
            'identities' => Identity::paginate(20),
            'hash' => $hash
        ]);
    }
}
