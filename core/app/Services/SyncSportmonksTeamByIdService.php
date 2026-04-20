<?php

namespace App\Services;

use App\Models\ActiveSeason;
use App\Models\Team;
use App\Models\Venue;
use Illuminate\Support\Facades\Http;

class SyncSportmonksTeamByIdService
{
    private ApiClientService $apiClient;

    public function __construct(protected ?string $token = null)
    {
        // $this->apiClient = $apiClient;
        $this->token = $this->token ?: config('services.SPORTMONKS_TOKEN');
    }

    public function handle($id)
    {
        $baseUrl = "https://api.sportmonks.com/v3/football/teams/{$id}";
        $primaryResponse = Http::timeout(60)
            ->retry(3, 1000)
            ->get($baseUrl, [
                'api_token' => $this->token,
                'locale'    => 'ar',
                'include'   => 'venue;activeSeasons',
            ]);

        $englishResponse = Http::timeout(60)
            ->retry(3, 1000)
            ->get($baseUrl, [
                'api_token' => $this->token,
                'locale'    => 'en',
                'include'   => 'venue',
            ]);

        if (!$primaryResponse->successful()) {
            throw new \RuntimeException(
                "SportMonks Arabic squad request failed. Status: {$primaryResponse->status()} Body: {$primaryResponse->body()}"
            );
        }

        if (!$englishResponse->successful()) {
            throw new \RuntimeException(
                "SportMonks English squad request failed. Status: {$englishResponse->status()} Body: {$englishResponse->body()}"
            );
        }

        $primaryRows = collect(data_get($primaryResponse->json(), 'data', []));
        $englishRows = collect(data_get($englishResponse->json(), 'data', []));
        $this->saveTeamData($primaryRows, $englishRows, $id);
    }

    public function saveTeamData($team, $englishTeam, $teamId)
    {
        $teamModel = Team::find($teamId);
        if ((bool) ($team['placeholder'] == false)) {
            if ($teamModel) {
                $teamModel->update([
                    'name_en'    => data_get($englishTeam, 'name'),
                    'image_path' => data_get($team, 'image_path'),
                    'country_id' => data_get($team, 'country_id'),
                    'venue_id'   => data_get($team, 'venue_id'),
                    'short_code' => data_get($team, 'short_code'),
                    'sport_id'   => data_get($team, 'sport_id'),
                    'type'       => data_get($team, 'type'),
                    'placeholder'=> data_get($team, 'placeholder'),
                ]);
            } else {
                $teamModel = Team::create([
                    'id'         => $teamId,
                    'name_ar'    => data_get($team, 'name'),
                    'name_en'    => data_get($englishTeam, 'name'),
                    'image_path' => data_get($team, 'image_path'),
                    'country_id' => data_get($team, 'country_id'),
                    'venue_id'   => data_get($team, 'venue_id'),
                    'short_code' => data_get($team, 'short_code'),
                    'sport_id'   => data_get($team, 'sport_id'),
                    'type'       => data_get($team, 'type'),
                    'placeholder'=> data_get($team, 'placeholder'),
                ]);
            }
            $this->saveVenueData(data_get($team, 'venue'), data_get($englishTeam, 'venue'));
            $this->saveActiveSeasons(data_get($team, 'activeseasons'), data_get($team, 'id'));

        }
    }

    public function saveVenueData($data, $englishData){
        $venue = Venue::find($data['id']);
        if($venue){
            $venue->update([
                'name_en'       => $englishData['name'],
                'country_id'    => $data['country_id'],
                'city_id'       => $data['city_id'],
                'address'       => $data['address'],
                'zipcode'       => $data['zipcode'],
                'latitude'      => $data['latitude'],
                'longitude'     => $data['longitude'],
                'capacity'      => $data['capacity'],
                'image_path'    => $data['image_path'],
                'city_name'     => $data['city_name'],
                'surface'       => $data['surface'],
                'national_team' => $data['national_team'],
            ]);
        }else{
            Venue::create([
                'id'            => $data['id'],
                'name_ar'       => $data['name'],
                'name_en'       => $englishData['name'],
                'country_id'    => $data['country_id'],
                'city_id'       => $data['city_id'],
                'address'       => $data['address'],
                'zipcode'       => $data['zipcode'],
                'latitude'      => $data['latitude'],
                'longitude'     => $data['longitude'],
                'capacity'      => $data['capacity'],
                'image_path'    => $data['image_path'],
                'city_name'     => $data['city_name'],
                'surface'       => $data['surface'],
                'national_team' => $data['national_team'],
            ]);
        }
    }
    public function saveActiveSeasons($data, $teamId){
        if(count($data) > 0){
            foreach($data as $item){
                ActiveSeason::updateOrCreate(
                    [
                        'team_id'   => $teamId,
                        'season_id' => $item['id'],
                    ],
                    [
                        'league_id' => $item['league_id'],
                    ]
                );
                $service = app(\App\Services\PlayerSyncService::class);
                $result = $service->syncByTeam(teamId: $teamId, seasonId: $item['id']);
            }
        }
    }
}
