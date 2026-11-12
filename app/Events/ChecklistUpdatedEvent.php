<?php declare(strict_types=1);

namespace App\Events;

use App\Models\Checklist;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Tells the overlay to re-render a checklist. $action is one of
 * added, done, undone, removed, reset, or updated; $itemId names the item it applies to.
 */
class ChecklistUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable;

    public int $checklistId;

    public function __construct(private Checklist $checklist, public string $action, public ?int $itemId = null)
    {
        $this->checklistId = $checklist->id;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('overlay.' . $this->checklist->user_id);
    }
}
