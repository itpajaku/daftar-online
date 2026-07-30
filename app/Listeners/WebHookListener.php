<?php

namespace App\Listeners;

use App\Models\WebHook;
use Illuminate\Support\Facades\Http;
use App\Models\WebHookLog;

class WebHookListener
{
  /**
   * Handle any EcourtAccount event (create, update, delete)
   */
  public function handle($event)
  {
    $eventClass = get_class($event);
    $webhooks = WebHook::where('event', $eventClass)->where('is_active', true)->get();
    foreach ($webhooks as $webhook) {
      $body = $webhook->body;
      // If event has ecourt_account property, replace variables
      $ecourtAccount = null;
      if (property_exists($event, 'ecourt_account')) {
          $ecourtAccount = $event->ecourt_account;
      } elseif (property_exists($event, 'ecourtAccount')) {
          $ecourtAccount = $event->ecourtAccount;
      }

      if ($ecourtAccount) {
          $identity = $ecourtAccount->identity;
          if ($identity) {
              $waNumber = preg_replace('/[^0-9]/', '', $identity->nomor_telepon_original);
              if (str_starts_with($waNumber, '0')) {
                  $waNumber = '62' . substr($waNumber, 1);
              }
              $body = str_replace('{NO_WA_USER}', $waNumber, $body);
          }
          $body = str_replace('{USER_ECOURT}', $ecourtAccount->username ?? '', $body);
          $body = str_replace('{PASS_ECOURT}', $ecourtAccount->password ?? '', $body);
      }
      $bodyArray = json_decode($body, TRUE);
      try {
        $request = Http::withHeaders([]);
        if (!empty($webhook->api_key)) {
            $headerName = $webhook->header_auth_name ?: 'Authorization';
            $request = $request->withHeaders([
                $headerName => $webhook->api_key
            ]);
        }

        if ($webhook->type === 'POST') {
          $response = $request->post($webhook->url, $bodyArray);
        } else {
          $response = $request->get($webhook->url, $bodyArray);
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
