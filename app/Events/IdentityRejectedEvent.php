<?php

namespace App\Events;

use App\Models\Identity;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IdentityRejectedEvent
{
    use Dispatchable, SerializesModels;

    public Identity $identity;

    public ?string $alasan_penolakan;

    public function __construct(Identity $identity, ?string $alasan_penolakan = null)
    {
        $this->identity = $identity;
        $this->alasan_penolakan = $alasan_penolakan;
    }
}
