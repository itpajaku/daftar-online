<?php

namespace App\Models;

use App\Service\HashId;
use App\Traits\UseHashedId;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Identity extends Model
{
    use UseHashedId;
    use SoftDeletes;

    protected $guarded = [];

    public function casts(): array
    {
        return [
            'tanggal_lahir' => 'datetime:Y-m-d'
        ];
    }

    protected function nomor_kependudukan()
    {
        return Attribute::make(
            get: fn(string $val) => Crypt::decryptString($val),
        );
    }

    public function getHashedIdAttribute()
    {
        return $this->hashId()->encode($this->id);
    }

    public function getNomorTeleponOriginalAttribute()
    {
        return Crypt::decryptString($this->nomor_telepon);
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
