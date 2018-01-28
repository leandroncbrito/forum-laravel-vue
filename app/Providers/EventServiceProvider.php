<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ThreadReceivedNewReply' => [
            'App\Listeners\NotifyThreadSubscribers',
            'App\Listeners\NotifyMentionedUsers'
        ],

        // Desnecessário criar um listener para um evento
        // Registered::class => [
        //     'App\Listeners\SendEmailConfirmationRequest'
        // ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
