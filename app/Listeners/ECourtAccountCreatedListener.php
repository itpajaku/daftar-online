<?php

namespace App\Listeners;

use App\Events\ECourtAccountCreatedEvent;
use App\Service\WhatsappClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ECourtAccountCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ECourtAccountCreatedEvent $event): void
    {
        $ecourt_account = $event->ecourt_account;
        $url = url('/timeline', ['hash_id' => $ecourt_account->identity->hashed_id]);
        $message = "*Akun Ecourt Anda Sudah Siap!*\nAkun E-Court pengguna lain anda sudah siap. Silahkan periksa disini {$url}\n\n_Pesan ini dikirim secara otomatis_";

        $wa = new WhatsappClient($ecourt_account->identity->nomor_telepon_original, $message);
        try {
            $wa->sendMessage();
        } catch (\Throwable $th) {
            Log::error("Whatsapp gagal dikirim ke user. Record id : {id}. Detail : {detail}", [
                'id' => $event->ecourt_account->id,
                'detail' => $th->getMessage()
            ]);
        }
    }
}
