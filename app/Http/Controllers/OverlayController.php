<?php

namespace App\Http\Controllers;

class OverlayController extends Controller
{

    public function __invoke()
    {
        return view('overlay');
    }
}
