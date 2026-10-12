<?php

namespace App\Providers;

use App\Events\ECourtAccountCreatedEvent;
use App\Events\EcourtAccountDeleteEvent;
use App\Events\EcourtAccountUpdateEvent;
use App\Events\IdentityCreateEvent;
use App\Events\IdentityDeleteEvent;
use App\Events\IdentityRejectedEvent;
use App\Events\IdentityUpdateEvent;
use App\Listeners\IdentityWebHookListener;
use App\Listeners\WebHookListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ECourtAccountCreatedEvent::class => [
            WebHookListener::class,
        ],
        EcourtAccountUpdateEvent::class => [
            WebHookListener::class,
        ],
        EcourtAccountDeleteEvent::class => [
            WebHookListener::class,
        ],
        IdentityCreateEvent::class => [
            IdentityWebHookListener::class,
        ],
        IdentityUpdateEvent::class => [
            IdentityWebHookListener::class,
        ],
        IdentityDeleteEvent::class => [
            IdentityWebHookListener::class,
        ],
        IdentityRejectedEvent::class => [
            IdentityWebHookListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
