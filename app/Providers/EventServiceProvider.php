<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\EcourtAccountCreateEvent;
use App\Events\EcourtAccountUpdateEvent;
use App\Events\EcourtAccountDeleteEvent;
use App\Listeners\WebHookListener;
use App\Events\IdentityCreateEvent;
use App\Events\IdentityUpdateEvent;
use App\Events\IdentityDeleteEvent;
use App\Listeners\IdentityWebHookListener;

class EventServiceProvider extends ServiceProvider
{
  protected $listen = [
    EcourtAccountCreateEvent::class => [
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
  ];

  public function boot(): void
  {
    parent::boot();
  }
}
