<?php

namespace App\Http\Controllers\Football;

use App\Http\Controllers\Controller;
use App\Services\ApiClientService;
use Illuminate\Http\Request;

class LeaguesController extends Controller
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->website_status();
    }


    public function index()
    {
        $leagues = \App\Models\League::with('country')->get();
        return view('frontEnd.custom.leagues', [
            'leagues' => $leagues,
            'count' => count($leagues),
        ]);
    }
}
