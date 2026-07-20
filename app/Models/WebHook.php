<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebHook extends Model
{
    protected $fillable = [
        'name',
        'url',
        'event',
        'type',
        'body',
        'is_active',
    ];
}
