<?php

namespace App\Service;

use App\Service\HashId;

class HashRouteId
{
  protected mixed $decodedId;
  protected mixed $originalId;

  public function __construct(protected HashId $hashid, mixed $id)
  {
    $this->originalId = $id;
    $this->decodedId = $this->hashid->decodeFirst(strval($id));
  }

  public function getDecodedId()
  {
    return $this->decodedId;
  }

  public function getOriginalId()
  {
    return $this->originalId;
  }
}
