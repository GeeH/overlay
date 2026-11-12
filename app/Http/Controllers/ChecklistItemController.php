<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ChecklistUpdatedEvent;
use App\Models\Checklist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistItemController extends Controller
{
    public function store(Request $request, int $checklistId): RedirectResponse
    {
        $checklist = $this->find($checklistId);
        $validated = $request->validate(['text' => 'required|string|max:255']);

        $item = $checklist->items()->create([
            'text'     => $validated['text'],
            'position' => (int) $checklist->items()->max('position') + 1,
        ]);
        ChecklistUpdatedEvent::dispatch($checklist, 'added', $item->id);

        return redirect()->route('checklists.show', $checklistId);
    }

    public function toggle(int $checklistId, int $itemId): RedirectResponse
    {
        $checklist = $this->find($checklistId);
        $item = $checklist->items()->findOrFail($itemId);
        $item->update(['done' => ! $item->done]);
        ChecklistUpdatedEvent::dispatch($checklist, $item->done ? 'done' : 'undone', $item->id);

        return redirect()->route('checklists.show', $checklistId);
    }

    /**
     * Swaps the item with its neighbour above or below, then renumbers every item
     * so positions stay unique.
     */
    public function move(Request $request, int $checklistId, int $itemId): RedirectResponse
    {
        $checklist = $this->find($checklistId);
        $direction = $request->validate(['direction' => 'required|in:up,down'])['direction'];

        $items = $checklist->items()->get()->values();
        $index = $items->search(fn ($item) => $item->id === $itemId);
        abort_if($index === false, 404);

        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if ($target >= 0 && $target < $items->count()) {
            $ordered = $items->all();
            [$ordered[$index], $ordered[$target]] = [$ordered[$target], $ordered[$index]];
            foreach ($ordered as $position => $item) {
                $item->update(['position' => $position + 1]);
            }
            ChecklistUpdatedEvent::dispatch($checklist, 'moved', $itemId);
        }

        return redirect()->route('checklists.show', $checklistId);
    }

    public function destroy(int $checklistId, int $itemId): RedirectResponse
    {
        $checklist = $this->find($checklistId);
        $item = $checklist->items()->findOrFail($itemId);
        $item->delete();
        ChecklistUpdatedEvent::dispatch($checklist, 'removed', $item->id);

        return redirect()->route('checklists.show', $checklistId);
    }

    private function find(int $checklistId): Checklist
    {
        return Checklist::where('user_id', '=', Auth::id())->findOrFail($checklistId);
    }
}
