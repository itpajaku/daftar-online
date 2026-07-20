<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EcourtAccountUpdateEvent
{
  use Dispatchable, SerializesModels;

  public $ecourtAccount;
  public $message;

  public function __construct($ecourtAccount, $message = null)
  {
    $this->ecourtAccount = $ecourtAccount;
    $this->message = $message;
  }
}
