<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IdentityCreateEvent
{
  use Dispatchable, SerializesModels;

  /**
   * @var \App\Models\Identity
   */
  public $identity;
  public $message;

  public function __construct(\App\Models\Identity $identity, $message = null)
  {
    $this->identity = $identity;
    $this->message = $message;
  }
}
