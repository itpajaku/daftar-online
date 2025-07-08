<?php

namespace App\Service;

use Vinkla\Hashids\HashidsManager;

class HashId
{
  public function __construct(protected HashidsManager $hashids) {}

  public function encode($value): string
  {
    return $this->hashids->encode($value);
  }

  public function decode(string $hash): array
  {
    return $this->hashids->decode($hash);
  }

  public function decodeFirst(string $hash): int
  {
    $decoded = $this->decode($hash);
    return count($decoded) > 0 ? $decoded[0] : 0;
  }
}
