<?php

namespace App\Console\Commands;

use App\Events\OverlayTriggerEvent;
use Illuminate\Console\Command;

class OverlayTriggerCommand extends Command
{
    protected $signature = 'overlay:trigger';

    protected $description = 'Command description';

    public function handle(): void
    {
        $result = OverlayTriggerEvent::dispatch(['something' => 'happened']);
        $this->output->info('Event dispatched ' . json_encode($result));
    }
}
