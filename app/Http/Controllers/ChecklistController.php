<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ChecklistUpdatedEvent;
use App\Models\Checklist;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    public function create(): View
    {
        return view('checklist', [
            'checklist' => new Checklist(['name' => '', 'theme' => 'default', 'top' => 80, 'left' => 80, 'width' => 520]),
            'themes'    => Checklist::themes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $checklist = new Checklist($this->validated($request));
        $checklist->user_id = Auth::id();
        $checklist->save();

        return redirect()->route('checklists.show', $checklist->id)->with('status', 'Checklist created.');
    }

    public function show(int $checklistId): View
    {
        return view('checklist', [
            'checklist' => $this->find($checklistId)->load('items'),
            'themes'    => Checklist::themes(),
        ]);
    }

    public function update(Request $request, int $checklistId): RedirectResponse
    {
        $checklist = $this->find($checklistId);
        $checklist->fill($this->validated($request))->save();
        ChecklistUpdatedEvent::dispatch($checklist, 'updated');

        return redirect()->route('checklists.show', $checklistId)->with('status', 'Checklist saved.');
    }

    public function reset(int $checklistId): RedirectResponse
    {
        $checklist = $this->find($checklistId);
        $checklist->items()->update(['done' => false]);
        ChecklistUpdatedEvent::dispatch($checklist, 'reset');

        return redirect()->route('checklists.show', $checklistId);
    }

    /**
     * The themed checklist alone, which the overlay fetches when it hears a ChecklistUpdatedEvent.
     */
    public function overlay(string $username, int $checklistId): View
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        $checklist = Checklist::where('user_id', '=', $user->id)->findOrFail($checklistId)->load('items');

        return view('checklist-themes.' . $checklist->theme, ['checklist' => $checklist]);
    }

    private function find(int $checklistId): Checklist
    {
        return Checklist::where('user_id', '=', Auth::id())->findOrFail($checklistId);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'  => 'required|string|max:255',
            'theme' => ['required', Rule::in(Checklist::themes())],
            'top'   => 'required|integer',
            'left'  => 'required|integer',
            'width' => 'required|integer|min:100',
        ]);
    }
}
