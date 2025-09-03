<?php

namespace App\Providers;

use App\Events\StudentViewed;
use App\Events\PdfReady;
use App\Listeners\IncrementStudentViews;
use App\Listeners\UpdateUserLastAccess;
use App\Listeners\CachePdfReadyForUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UpdateUserLastAccess::class,
    ];

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        StudentViewed::class => [
            IncrementStudentViews::class,
        ],
        PdfReady::class => [
            CachePdfReadyForUser::class,
        ],
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
