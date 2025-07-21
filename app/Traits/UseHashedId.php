<?php

namespace App\Traits;

use App\Service\HashId;

trait UseHashedId
{
  public function hashId(): HashId
  {
    return app(HashId::class);
  }
}
