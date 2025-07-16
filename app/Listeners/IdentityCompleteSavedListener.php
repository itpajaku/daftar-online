<?php

namespace App\Listeners;

use App\Events\IdentityCompleteSavedEvent;
use App\Service\WhatsappClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class IdentityCompleteSavedListener
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
    public function handle(IdentityCompleteSavedEvent $event): void
    {
        $identity = $event->bankAccount->identity;
        $waClient = new WhatsappClient(
            app('settings')['wa_admin_ecourt'],
            "*Notifikasi Pendaftaran E-Court*\n\nTerdapat permohonan pembuatan akun E-Court pengguna lain atas nama {$identity->nama_lengkap}. Silahkan verifikasi data di " . url('/login')
        );

        try {
            $waClient->sendMessage();
        } catch (\Throwable $th) {
            Log::error("Whatsapp gagal dikirim ke admin E-Court. Record id : {id}. Detail : {detail}", [
                'id' => $event->bankAccount->id,
                'detail' => $th->getMessage()
            ]);
        }
    }
}
