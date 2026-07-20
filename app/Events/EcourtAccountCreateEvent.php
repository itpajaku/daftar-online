<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EcourtAccountCreateEvent
{
  use Dispatchable, SerializesModels;

  public function __construct(public $ecourtAccount, public $message = null) {}
}
