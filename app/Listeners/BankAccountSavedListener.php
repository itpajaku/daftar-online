<?php

namespace App\Listeners;

use App\Events\BankAccountSavedEvent;
use App\Models\SensitiveBankAccountKey;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Crypt;

class BankAccountSavedListener
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
    public function handle(BankAccountSavedEvent $event): void
    {
        SensitiveBankAccountKey::updateOrCreate(
            ['bank_account_id' => $event->bankAccount->id],
            [
                'hash_nomor_rekening' => hash_hmac(
                    'sha256',
                    $event->bankAccount->nomor_rekening,
                    config('app.key')
                )
            ]
        );
    }
}
