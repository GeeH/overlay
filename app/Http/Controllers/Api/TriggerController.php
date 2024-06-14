<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\OverlayTriggerEvent;
use App\Http\Controllers\Controller;
use App\Models\Pane;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TriggerController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $paneId = $request->get('pane', false);
        if ($paneId === false) {
            return new JsonResponse(['error' => 'No such pane'], 404);
        }

        try {
            $pane = Pane::findOrFail($paneId);
            $pane->elementId = str_replace(' ', '-', $pane->name);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }

        OverlayTriggerEvent::dispatch($pane->toArray());
        Log::info('Triggering Response');
        return new JsonResponse('OK');
    }
}
