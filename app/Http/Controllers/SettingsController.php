<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'global_font' => 'nullable|string|max:255',
        ]);

        Auth::user()->update(['global_font' => $validated['global_font'] ?? '']);

        return redirect()->route('dashboard')->with('status', 'Settings saved.');
    }
}
