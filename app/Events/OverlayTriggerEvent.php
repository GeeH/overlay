<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class OverlayTriggerEvent implements ShouldBroadcast
{
    use Dispatchable;

    public function __construct(public array $data = ['no data'])
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('overlay.1');
    }
}
