<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsappClient
{
  protected $baseUrl;

  public function __construct(
    protected string $phone_number,
    protected string $message
  ) {
    $this->baseUrl = config('services.wa_api');
    if ($this->phone_number[0] == '0') {
      $this->phone_number = Str::replaceFirst('0', '62', $this->phone_number);
    }
    $this->phone_number = $this->phone_number . "@s.whatsapp.net";
  }

  public function sendMessage()
  {
    $waResponse = Http::withBasicAuth('username', 'password')->post($this->baseUrl . '/send/message', [
      "phone" => $this->phone_number,
      "message" => $this->message,
      "is_forwarded" => false
    ]);

    if ($waResponse->successful()) {
      return $waResponse->object();
    } else {
      $waResponse->throw();
    }
  }
}
