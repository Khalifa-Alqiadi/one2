<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    public function set(Request $request)
    {
        session(['user_timezone' => $request->timezone]);
        return response()->json(['ok' => true]);
    }
}
