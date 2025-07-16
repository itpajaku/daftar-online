<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcourtAccount extends Model
{
    protected $guarded = [];

    public function identity()
    {
        return $this->belongsTo(Identity::class, 'identity_id');
    }
}
