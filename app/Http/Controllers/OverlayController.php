<?php

namespace App\Http\Controllers;

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

        return view('overlay', ['user' => $user, 'panes' => $panes, 'debug' => $request->get('debug', false) !== false]);
    }
}
