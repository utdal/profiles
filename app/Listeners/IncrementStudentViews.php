<?php

namespace App\Listeners;

use App\Events\StudentViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncrementStudentViews
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(StudentViewed $event)
    {
        $event->student->incrementViews();
        $event->student->updateLastViewed();
    }
}
