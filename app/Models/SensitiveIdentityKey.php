<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensitiveIdentityKey extends Model
{
    protected $guarded = [];

    public function identity()
    {
        return $this->belongsTo(Identity::class, 'identities_id', 'id');
    }
}
