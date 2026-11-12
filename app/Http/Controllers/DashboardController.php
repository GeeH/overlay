<?php declare(strict_types=1);


namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Pane;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $panes = Pane::where('user_id', '=', Auth::id())
            ->get();
        $checklists = Checklist::where('user_id', '=', Auth::id())
            ->withCount(['items', 'items as done_count' => fn ($query) => $query->where('done', true)])
            ->get();
        return \view('dashboard', ['panes' => $panes, 'checklists' => $checklists, 'user' => Auth::user()]);
    }
}
