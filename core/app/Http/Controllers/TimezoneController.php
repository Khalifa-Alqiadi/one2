<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    public function set(Request $request)
    {
        return response()->json(['ok' => true])
            ->cookie('user_timezone', $request->timezone, 60 * 24 * 30);
    }
}
