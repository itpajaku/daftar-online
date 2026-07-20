<?php

namespace App\Listeners;

use App\Events\IdentityCreateEvent;
use App\Models\WebHook;
use Illuminate\Support\Facades\Http;
use App\Models\WebHookLog;

class IdentityWebHookListener
{
  public function handle(IdentityCreateEvent $event)
  {
    $eventClass = get_class($event);
    $webhooks = WebHook::where('event', $eventClass)->where('is_active', true)->get();
    foreach ($webhooks as $webhook) {
      $body = $webhook->body;
      // If event has identity property, replace variables
      // if (property_exists($event, 'identity') && $event->identity) {
      //   $identity = $event->identity;
      //   $body = str_replace('$_IDENTITY_ID_$', $identity->id, $body);
      //   $body = str_replace('$_IDENTITY_PHONE_$', $identity->phone ?? '', $body);
      // }
      $bodyArray = json_decode($body, TRUE);
      try {
        if ($webhook->type === 'POST') {
          $response = Http::post($webhook->url, $bodyArray);
        } else {
          $response = Http::get($webhook->url, $bodyArray);
        }
        WebHookLog::create([
          'web_hook_id' => $webhook->id,
          'event' => $eventClass,
          'payload' => $bodyArray,
          'status_code' => $response->status(),
          'response' => $response->body(),
          'success' => $response->successful(),
        ]);
      } catch (\Throwable $th) {
        WebHookLog::create([
          'web_hook_id' => $webhook->id,
          'event' => $eventClass,
          'payload' => $bodyArray,
          'success' => false,
          'error_message' => $th->getMessage(),
        ]);
      }
    }
  }
}
