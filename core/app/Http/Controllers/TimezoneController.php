<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    public function set(Request $request)
    {
        request()->cookie('user_timezone', $request->timezone, 60 * 24 * 30);
        return response()->json(['ok' => true]);
    }
}
