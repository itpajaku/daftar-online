<?php

namespace App\Models;

use App\Events\ECourtAccountCreatedEvent;
use Illuminate\Database\Eloquent\Model;

class EcourtAccount extends Model
{
    protected $guarded = [];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'created' => ECourtAccountCreatedEvent::class,
    ];

    public function identity()
    {
        return $this->belongsTo(Identity::class, 'identity_id');
    }
}
