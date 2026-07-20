<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebHookLog extends Model
{
  protected $guarded = [];

  protected $casts = [
    'payload' => 'array',
  ];

  public function webhook()
  {
    return $this->belongsTo(WebHook::class, 'web_hook_id');
  }
}
