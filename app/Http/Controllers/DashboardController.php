<?php declare(strict_types=1);


namespace App\Http\Controllers;

use App\Models\Pane;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $panes = Pane::where('user_id', '=', Auth::id())
            ->get();
        return \view('dashboard', ['panes' => $panes, 'user' => Auth::user()]);
    }
}
