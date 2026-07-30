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
        'api_key',
        'header_auth_name',
        'body',
        'is_active',
    ];
}
