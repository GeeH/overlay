<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\OverlayTriggerEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TriggerController extends Controller
{
    public function __invoke(): JsonResponse
    {
        OverlayTriggerEvent::dispatch(['something' => 'happened']);
        return new JsonResponse('OK');
    }
}
