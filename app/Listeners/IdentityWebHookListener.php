<?php

namespace App\Listeners;

use App\Models\WebHook;
use App\Models\WebHookLog;
use Illuminate\Support\Facades\Http;

class IdentityWebHookListener
{
    public function handle($event)
    {
        $eventClass = get_class($event);
        $webhooks = WebHook::where('event', $eventClass)->where('is_active', true)->get();
        foreach ($webhooks as $webhook) {
            $body = $webhook->body;

            if (property_exists($event, 'identity') && $event->identity) {
                $identity = $event->identity;
                $waNumber = preg_replace('/[^0-9]/', '', $identity->nomor_telepon_original ?? '');
                if (str_starts_with($waNumber, '0')) {
                    $waNumber = '62'.substr($waNumber, 1);
                }
                $body = str_replace('{NO_WA_USER}', $waNumber, $body);
                $body = str_replace('{NAMA_USER}', $identity->nama_lengkap ?? '', $body);
                $body = str_replace('{ALASAN_PENOLAKAN}', $event->alasan_penolakan ?? $identity->alasan_penolakan ?? '', $body);
            }

            $bodyArray = json_decode($body, true);
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
