<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SyncSportmonksPlayers
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function handle($id){

    }
}
