<?php

namespace App\Http\Controllers;

use App\Models\Pane;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverlayController extends Controller
{

    public function __invoke(Request $request)
    {
        $panes = Pane::where('user_id', '=', 1)
            ->get();

        return view('overlay', ['panes' => $panes, 'debug' => $request->get('debug', false) !== false]);
    }
}
