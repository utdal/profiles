<?php

namespace App\Listeners;

use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserLastAccess
{
    /** @var Carbon the current datetime */
    protected $now;

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $calls = self::class;

        $events->listen(Login::class, $calls . '@updateUserLastLogin');
        $events->listen(Logout::class, $calls . '@updateUserLastLogout');

    }

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->now = Carbon::now();
    }

    /**
     * Updates the User's last login.
     * 
     * @param  Login  $event
     */
    public function updateUserLastLogin(Login $event)
    {
        $user = $event->user;

        $this->setLastAccess($user);
    }

    /**
     * Updates the User's last logout.
     * 
     * @param  Logout $event
     */
    public function updateUserLastLogout(Logout $event)
    {
        $user = $event->user;

        if ($user === null || !is_object($user) || !($user instanceof User)) {
            return;
        }

        $this->setLastAccess($user);
    }

    /**
     * Sets the User record last_access attribute.
     * 
     * @param User $user
     * @return bool
     */
    protected function setLastAccess(User $user)
    {
        return $user->setAttribute('last_access', $this->now)->save();
    }

}
