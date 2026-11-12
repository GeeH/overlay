<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Pane;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverlayController extends Controller
{

    public function __invoke(Request $request, string $username)
    {
        $user = User::where('username', '=', $username)
            ->firstOrFail();

        $panes = Pane::where('user_id', '=', $user->id)
            ->get();

        $checklists = Checklist::where('user_id', '=', $user->id)
            ->with('items')
            ->get();

        return view('overlay', [
            'user' => $user,
            'panes' => $panes,
            'checklists' => $checklists,
            'debug' => $request->get('debug', false) !== false,
        ]);
    }
}
