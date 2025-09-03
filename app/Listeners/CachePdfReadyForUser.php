<?php

namespace App\Listeners;

use App\Events\PdfReady;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class CachePdfReadyForUser implements ShouldQueue
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
    public function handle(PdfReady $event): void
    {
        $url = URL::temporarySignedRoute(
                    $event->route_name,
                    now()->addMinutes(30),
                    ['path' => $event->temp_path, 'name' => $event->filename, 'user' => $event->user, 'profile' => $event->profile]
                );

        $download = ['url' => $url, 'filename' => $event->filename, 'user' => $event->user, 'profile' => $event->profile, 'description' => $event->description];

        Cache::put("pdf:ready:{$event->token}", $download, now()->addMinutes(30));
    }

}
