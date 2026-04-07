<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    public function set(Request $request, $timezone)
    {
        request()->cookie('user_timezone', $timezone, 60 * 24 * 30);
        return response()->json(['ok' => true]);
    }
}
