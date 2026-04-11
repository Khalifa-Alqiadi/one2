<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\League;
use App\Models\Season;

class UpdatesLeaguesAndSeasonsServices
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function loadLeagues()
    {
        $token = config('services.SPORTMONKS_TOKEN');

        // الطلب الأساسي: كل الحقول
        $primaryLocale = 'ar';
        $primaryUrl = "https://api.sportmonks.com/v3/football/leagues"
            . "?api_token={$token}"
            . "&locale={$primaryLocale}";

        // الطلب الثاني: فقط الاسم الإنجليزي
        $englishUrl = "https://api.sportmonks.com/v3/football/leagues"
            . "?api_token={$token}"
            . "&locale=en";

        $primaryRes = $this->apiClient->curlGet($primaryUrl);
        $englishRes = $this->apiClient->curlGet($englishUrl);

        $primaryData = collect(data_get($primaryRes, 'json.data', []));
        $englishData = collect(data_get($englishRes, 'json.data', []));

        // نخلي الإنجليزي مفهرس بالـ id عشان نجيبه بسرعة
        $englishMap = $englishData->keyBy('id');

        $leagues = $primaryData
            ->map(function ($league) use ($englishMap) {
                $leagueId = data_get($league, 'id');

                $englishLeague = $englishMap->get($leagueId, []);

                $seasons = collect(data_get($league, 'seasons', []));
                $foundedYear = $seasons
                    ->pluck('starting_at')
                    ->filter()
                    ->map(fn($d) => (int) substr($d, 0, 4))
                    ->sort()
                    ->first();

                return [
                    'id' => $leagueId,

                    // الأسماء
                    'name_ar' => data_get($league, 'name'),
                    'name_en' => data_get($englishLeague, 'name'),

                    // باقي الحقول من الطلب الأول
                    'short_code' => data_get($league, 'short_code'),
                    'image_path' => data_get($league, 'image_path'),
                    'country_id' => data_get($league, 'country_id'),
                    'sport_id' => data_get($league, 'sport_id'),
                    'type' => data_get($league, 'type'),
                    'sub_type' => data_get($league, 'sub_type'),
                    'last_played_at' => data_get($league, 'last_played_at'),
                    'category' => data_get($league, 'category'),
                    'has_jerseys' => data_get($league, 'has_jerseys'),
                    'founded_year_estimated' => $foundedYear,

                    // الخام كامل لو احتجته لاحقًا
                    'raw' => $league,
                ];
            })
            ->values();

        foreach ($leagues as $league) {
            $existLeague = League::find($league['id']);
            if($existLeague){
                $existLeague->update([
                    'name_en' => $league['name_en'],
                    'image_path' => $league['image_path'],
                    'country_id' => $league['country_id'],
                    'sport_id' => $league['sport_id'],
                ]);
            }else{
                League::create(
                    [
                        'name_ar' => $league['name_ar'],
                        'name_en' => $league['name_en'],
                        'image_path' => $league['image_path'],
                        'country_id' => $league['country_id'],
                        'sport_id' => $league['sport_id'],
                    ]
                );
            }
            // $this->loadSeasons($league['id']);
        }
    }

    public function loadSeasons($leagueId){
        $token  = config('services.SPORTMONKS_TOKEN');

        $page  = 1;
        $saved = 0;
        do {
            $url = "https://api.sportmonks.com/v3/football/seasons"
                . "?api_token={$token}"
                . "&page={$page}";

            $res  = $this->apiClient->curlGet($url);
            $json = data_get($res, 'json', []);
            $data = data_get($json, 'data', []);

            $seasons = collect($data)
                ->filter(fn($s) => (int) data_get($s, 'league_id', 0) === (int) $leagueId)
                ->values();

            foreach ($seasons as $season) {
                Season::updateOrCreate(
                    ['id' => $season['id']],
                    [
                        'league_id'   => $season['league_id'],
                        'name'        => $season['name'],
                        'starting_at' => $season['starting_at'],
                        'ending_at'   => $season['ending_at'],
                        'is_current'  => $season['is_current'],
                    ]
                );

                $saved++;
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);
    }
}
