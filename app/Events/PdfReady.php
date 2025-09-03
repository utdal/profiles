<?php

namespace App\Events;

use App\Profile;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PdfReady
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public Profile $profile, public string $route_name, public string $temp_path, public string $filename, public string $description, public string $token){}


}
