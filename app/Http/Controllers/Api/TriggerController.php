<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\OverlayTriggerEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class TriggerController extends Controller
{
    public function __invoke(): JsonResponse
    {
        OverlayTriggerEvent::dispatch(['something' => 'happened']);
        Log::info('Triggering Response');
        return new JsonResponse('OK');
    }
}
