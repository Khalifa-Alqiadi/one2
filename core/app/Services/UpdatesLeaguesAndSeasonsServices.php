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
            . "&locale={$primaryLocale}"
            . "&include=seasons"; // نجيب المواسم مع كل دوري عشان نقدر نحسب سنة التأسيس التقريبية

        // الطلب الثاني: فقط الاسم الإنجليزي
        $englishUrl = "https://api.sportmonks.com/v3/football/leagues"
            . "?api_token={$token}"
            . "&locale=en"
            . "&include=seasons";

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
                    'seasons' => $seasons->toArray(),
                ];
            })
            ->values();
        $saved = 0;
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
                        'id' => $league['id'],
                        'name_ar' => $league['name_ar'],
                        'name_en' => $league['name_en'],
                        'image_path' => $league['image_path'],
                        'country_id' => $league['country_id'],
                        'sport_id' => $league['sport_id'],
                    ]
                );
            }
            $this->loadSeasons($league['seasons']);
            $saved++;
        }
        return $saved;
    }

    public function loadSeasonsForLeague(int $leagueId, string $locale = 'ar'): int
    {
        if ($leagueId <= 0) {
            return 0;
        }

        $token = (string) config('services.SPORTMONKS_TOKEN');

        if ($token === '') {
            return 0;
        }

        $url = "https://api.sportmonks.com/v3/football/leagues/{$leagueId}"
            . "?api_token={$token}"
            . "&locale={$locale}"
            . "&include=seasons";

        $response = $this->apiClient->curlGet($url);

        if (!data_get($response, 'ok')) {
            return 0;
        }

        $seasons = data_get($response, 'json.data.seasons', []);
        $this->loadSeasons($seasons);

        return is_countable($seasons) ? count($seasons) : 0;
    }

    public function loadSeasons($seasons){
        if(empty($seasons)){
            return;
        }
        foreach ($seasons as $season) {
            $seasonId = (int) data_get($season, 'id', 0);

            if ($seasonId <= 0) {
                continue;
            }

            Season::updateOrCreate(
                ['id' => $seasonId],
                [
                    'league_id'   => data_get($season, 'league_id'),
                    'name'        => data_get($season, 'name'),
                    'starting_at' => data_get($season, 'starting_at'),
                    'ending_at'   => data_get($season, 'ending_at'),
                    'is_current'  => (bool) data_get($season, 'is_current', false),
                ]
            );
        }
    }
}
