<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\NewResource;
use App\Events\NewScopeEntry;
use App\Events\NewService;
use App\Events\NewJob;
use App\Listeners\ResourceNotification;
use App\Listeners\ScopeEntryNotification;
use App\Listeners\ServiceNotification;
use App\Listeners\MakeJobNotification;

use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Queue;
use App\Models\Report;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
		  NewResource::class => [
        ResourceNotification::class,
    ],
	  NewScopeEntry::class => [
        ScopeEntryNotification::class,
    ],
		  NewService::class => [
        ServiceNotification::class,
    ],
		  NewJob::class => [
        MakeJobNotification::class,
    ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
