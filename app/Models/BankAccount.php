<?php

namespace App\Models;

use App\Events\BankAccountSavedEvent;
use App\Events\IdentityCompleteSavedEvent;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => IdentityCompleteSavedEvent::class,
        'saved' => BankAccountSavedEvent::class
    ];

    public function sensitive_bank_account_key()
    {
        return $this->hasOne(SensitiveBankAccountKey::class, 'bank_account_id', 'id');
    }

    public function identity()
    {
        return $this->belongsTo(Identity::class, "identity_id", "id");
    }
}
