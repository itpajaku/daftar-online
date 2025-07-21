<?php

namespace App\Models;

use App\Events\BankAccountSavedEvent;
use App\Events\IdentityCompleteSavedEvent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class BankAccount extends Model
{
    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => IdentityCompleteSavedEvent::class,
        'saved' => BankAccountSavedEvent::class
    ];

    protected function nomorRekening(): Attribute
    {
        return Attribute::make(get: function ($val) {
            return Crypt::decryptString($val);
        });
    }

    public function sensitive_bank_account_key()
    {
        return $this->hasOne(SensitiveBankAccountKey::class, 'bank_account_id', 'id');
    }

    public function identity()
    {
        return $this->belongsTo(Identity::class, "identity_id", "id");
    }
}
