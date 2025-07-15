<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $guarded = [];

    public function sensitive_bank_account_key()
    {
        return $this->hasOne(SensitiveBankAccountKey::class, 'bank_account_id', 'id');
    }
}
