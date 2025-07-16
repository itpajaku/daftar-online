<?php

namespace App\Models;

use App\Service\HashId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Identity extends Model
{
    protected $guarded = [];

    public function getNomorTeleponOriginalAttribute(HashId $hash)
    {
        return $hash->decodeFirst($this->nomor_telepon);
    }

    public function getNomorKependudukanOriginalAttribute()
    {
        return Crypt::decryptString($this->nomor_kependudukan);
    }

    public function bank_account()
    {
        return $this->hasOne(BankAccount::class, 'identity_id', 'id');
    }

    public function sensitive_identity_key()
    {
        return $this->hasOne(SensitiveIdentityKey::class, 'identities_id', 'id');
    }

    public function ecourt_account()
    {
        return $this->hasOne(EcourtAccount::class, 'identity_id', 'id');
    }
}
