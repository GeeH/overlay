<?php declare(strict_types=1);


namespace App\Http\Controllers;

use App\Models\Pane;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AddEditPaneController extends Controller
{
    public function show(Request $request, int $paneId): View
    {
        $pane = Pane::where('user_id', '=', Auth::id())
            ->where('id', '=', $paneId)
            ->firstOrFail();

        return view('add-edit-pane', ['pane' => $pane]);
    }

    public function update(Request $request, int $paneId): RedirectResponse
    {
        $pane = Pane::where('user_id', '=', Auth::id())
            ->where('id', '=', $paneId)
            ->firstOrFail();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'text'         => 'required|string',
            'size'         => 'required|string|max:50',
            'colour'       => 'required|string|max:50',
            'bgColour'     => 'required|string|max:50',
            'font'         => 'nullable|string|max:255',
            'top'          => 'required|integer',
            'left'         => 'required|integer',
            'width'        => 'required|integer',
            'height'       => 'required|integer',
            'animationIn'  => 'required|string|max:255',
            'animationOut' => 'nullable|string|max:255',
            'showFor'      => 'required|integer|min:1',
            'alwaysShown'  => 'boolean',
            'extraCss'     => 'nullable|string',
            'extraClasses' => 'nullable|string',
        ]);

        $validated['alwaysShown'] = $request->boolean('alwaysShown');
        $validated['font']        = $validated['font'] ?? '';
        $validated['animationOut'] = $validated['animationOut'] ?? '';
        $validated['extraCss']    = $validated['extraCss'] ?? '';
        $validated['extraClasses'] = $validated['extraClasses'] ?? '';

        $pane->fill($validated)->save();

        return redirect()->route('add-edit-pane', $paneId)->with('status', 'Pane saved.');
    }
}
