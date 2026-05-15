<?php

namespace App\Services;

class UpdateRoundsAndFixturesService
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }
    public function syncStagesAPI($league, int $seasonId, string $token, string $locale = 'ar'): void
    {
        $page = 1;

        do {
            $primaryUrl = "https://api.sportmonks.com/v3/football/stages/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale=ar"
                . "&page={$page}";

            $englishUrl = "https://api.sportmonks.com/v3/football/stages/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale=en"
                . "&page={$page}";

            $primaryRes = $this->apiClient->curlGet($primaryUrl);
            $englishRes = $this->apiClient->curlGet($englishUrl);

            $primaryJson = data_get($primaryRes, 'json', []);
            $englishJson = data_get($englishRes, 'json', []);

            $primaryData = collect(data_get($primaryJson, 'data', []));
            $englishData = collect(data_get($englishJson, 'data', []));

            // فهرسة الإنجليزي حسب id
            $englishMap = $englishData->keyBy('id');

            $items = $primaryData
                ->filter(fn($item) => (int) data_get($item, 'league_id', 0) === (int) $league->id)
                ->map(function ($item) use ($englishMap) {
                    $stageId = (int) data_get($item, 'id');
                    $englishStage = $englishMap->get($stageId, []);

                    return [
                        'id'          => $stageId,
                        'league_id'   => (int) data_get($item, 'league_id'),
                        'season_id'   => (int) data_get($item, 'season_id'),
                        'name_ar'     => data_get($item, 'name'),
                        'name_en'     => data_get($englishStage, 'name'),
                        'type_id'     => data_get($item, 'type_id'),
                        'type_name'   => data_get($item, 'type.name', data_get($item, 'type', null)),
                        'sort_order'  => data_get($item, 'sort_order'),
                        'finished'    => (bool) data_get($item, 'finished', false),
                        'is_current'  => (bool) data_get($item, 'is_current', false),
                        'starting_at' => data_get($item, 'starting_at'),
                        'ending_at'   => data_get($item, 'ending_at'),
                        'raw'         => $item,
                    ];
                })
                ->values();

            foreach ($items as $stage) {

                // إنشاء مع العربي
                \App\Models\Stage::updateOrCreate(
                    ['id' => (int) data_get($stage, 'id')],
                    [
                        'id'          => $stage['id'],
                        'league_id'   => $stage['league_id'],
                        'season_id'   => $stage['season_id'],
                        'name_ar'     => $stage['name_ar'],
                        'name_en'     => $stage['name_en'],
                        'type_id'     => $stage['type_id'],
                        'type_name'   => $stage['type_name'],
                        'sort_order'  => $stage['sort_order'],
                        'finished'    => $stage['finished'],
                        'is_current'  => $stage['is_current'],
                        'starting_at' => $stage['starting_at'],
                        'ending_at'   => $stage['ending_at'],
                    ]
                );
            }

            $hasMore = (bool) data_get($primaryJson, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);
    }

    public function syncRoundsBySeasonAPI($league, int $seasonId, string $token, string $locale): void
    {
        $page = 1;

        do {
            $url = "https://api.sportmonks.com/v3/football/rounds/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale={$locale}"
                . "&include=fixtures"
                . "&page={$page}";

            $res = $this->apiClient->curlGet($url);
            $json = data_get($res, 'json', []);
            $rounds = collect(data_get($json, 'data', []))
                ->filter(fn($r) => (int) data_get($r, 'league_id', 0) === (int) $league->id)
                ->unique('id')
                ->values();

            foreach ($rounds as $round) {
                $this->matchesAPI(data_get($round, 'fixtures', []), $token, $locale);

                \App\Models\Round::updateOrCreate(
                    ['id' => (int) data_get($round, 'id')],
                    [
                        'league_id'              => (int) $league->id,
                        'season_id'              => data_get($round, 'season_id'),
                        'stage_id'               => data_get($round, 'stage_id'),
                        'name'                   => data_get($round, 'name'),
                        'finished'               => (bool) data_get($round, 'finished', false),
                        'is_current'             => (bool) data_get($round, 'is_current', false),
                        'games_in_current_week'  => (bool) data_get($round, 'games_in_current_week', false),
                        'starting_at'            => data_get($round, 'starting_at'),
                        'ending_at'              => data_get($round, 'ending_at'),
                    ]
                );
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);
    }

    public function syncGroupsBySeasonAPI($league, int $seasonId, string $token): void
    {
        $page = 1;

        do {
            // نجلب الـ stages مع include=groups للحصول على المجموعات
            $arUrl = "https://api.sportmonks.com/v3/football/stages/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale=ar"
                . "&include=groups"
                . "&page={$page}";

            $enUrl = "https://api.sportmonks.com/v3/football/stages/seasons/{$seasonId}"
                . "?api_token={$token}"
                . "&locale=en"
                . "&include=groups"
                . "&page={$page}";

            $arRes  = $this->apiClient->curlGet($arUrl);
            $enRes  = $this->apiClient->curlGet($enUrl);

            $arJson = data_get($arRes, 'json', []);
            $enJson = data_get($enRes, 'json', []);

            if (data_get($arJson, 'message') || !is_array(data_get($arJson, 'data'))) {
                return;
            }

            // بناء خريطة الأسماء الإنجليزية للمجموعات
            $enGroupsMap = [];
            foreach (data_get($enJson, 'data', []) as $enStage) {
                foreach (data_get($enStage, 'groups', []) as $enGroup) {
                    $enGroupsMap[(int) data_get($enGroup, 'id')] = data_get($enGroup, 'name');
                }
            }

            $stages = collect(data_get($arJson, 'data', []))
                ->filter(fn($s) => (int) data_get($s, 'league_id', 0) === (int) $league->id)
                ->values();

            foreach ($stages as $stage) {
                $stageId   = (int) data_get($stage, 'id');
                $groupsRaw = data_get($stage, 'groups', []);

                if (!is_array($groupsRaw) || empty($groupsRaw)) {
                    continue;
                }

                foreach ($groupsRaw as $group) {
                    $groupId = (int) data_get($group, 'id', 0);
                    if ($groupId <= 0) continue;

                    \App\Models\Group::updateOrCreate(
                        ['id' => $groupId],
                        [
                            'league_id'   => (int) $league->id,
                            'season_id'   => $seasonId,
                            'stage_id'    => $stageId ?: null,
                            'name_ar'     => data_get($group, 'name'),
                            'name_en'     => $enGroupsMap[$groupId] ?? data_get($group, 'name'),
                            'sort_order'  => data_get($group, 'sort_order'),
                            'finished'    => (bool) data_get($group, 'finished', false),
                            'is_current'  => (bool) data_get($group, 'is_current', false),
                            'starting_at' => data_get($group, 'starting_at'),
                            'ending_at'   => data_get($group, 'ending_at'),
                        ]
                    );
                }
            }

            $hasMore = (bool) data_get($arJson, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);
    }

    public function matchesAPI($fixtures, $token, $locale)
    {
        foreach ($fixtures as $fx) {
            $fixtureId = (int) data_get($fx, 'id', 0);
            if ($fixtureId <= 0) continue;

            $fixtureUrl = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=participants;state;scores;periods;events";

            $fxRes = $this->apiClient->curlGet($fixtureUrl);
            if (!data_get($fxRes, 'ok')) continue;

            $match = data_get($fxRes, 'json.data', []);
            if (!$match) continue;

            // participants home/away
            $participants = collect(data_get($match, 'participants', []));
            $home = $participants->first(fn($p) => data_get($p, 'meta.location') === 'home');
            $away = $participants->first(fn($p) => data_get($p, 'meta.location') === 'away');

            $homeId = data_get($home, 'id') ?: data_get($home, 'participant_id');
            $awayId = data_get($away, 'id') ?: data_get($away, 'participant_id');

            // state
            $stateId   = (int) (data_get($match, 'state_id') ?: data_get($match, 'state.id'));
            $stateName = (string) (data_get($match, 'state.name') ?: data_get($match, 'state_name') ?: '');

            // ✅ state_code extraction (robust)
            $rawCode = data_get($match, 'state.short_code')
                ?? data_get($match, 'state.code')
                ?? data_get($match, 'state.developer_name')
                ?? data_get($match, 'state.state')
                ?? null;

            $stateCode = app(LiveMatchesService::class)->normalizeStateCode($rawCode, $stateName);

            // ✅ finished/live flags تعتمد على code
            $isFinished = app(LiveMatchesService::class)->isFinishedState($stateCode, $stateName, (string)data_get($match, 'result_info', ''));
            // $isLive     = $this->isLiveState($stateCode, $stateName);

            // scores
            $scoresArr = data_get($match, 'scores', []) ?? [];
            [$homeScore, $awayScore] = app(LiveMatchesService::class)->extractGoalsFromScores($scoresArr);
            // ft scores
            $ftHome = $isFinished && is_numeric($homeScore) ? (int)$homeScore : null;
            $ftAway = $isFinished && is_numeric($awayScore) ? (int)$awayScore : null;

            // minute (live)
            $minute = data_get($match, 'periods.minute');
            $minute = is_numeric($minute) ? (int)$minute : null;
            \App\Models\Fixture::updateOrCreate(
                ['id' =>  $match['id']],
                [
                    'league_id'     => (int) data_get($match, 'league_id'),
                    'season_id'     => (int) data_get($match, 'season_id'),
                    'round_id'      => data_get($match, 'round_id') ? (int) data_get($match, 'round_id') : null,
                    'stage_id'      => data_get($match, 'stage_id') ? (int) data_get($match, 'stage_id') : null,
                    'group_id'      => data_get($match, 'group_id') ? (int) data_get($match, 'group_id') : null,

                    'home_team_id'  => $homeId ? (int)$homeId : null,
                    'away_team_id'  => $awayId ? (int)$awayId : null,

                    'starting_at'   => data_get($match, 'starting_at'),

                    'state_id'      => $stateId ?: null,
                    'state_name'    => $stateName ?: null,
                    'state_code'    => $stateCode ?: null,

                    'home_score'    => is_numeric($homeScore) ? (int)$homeScore : null,
                    'away_score'    => is_numeric($awayScore) ? (int)$awayScore : null,

                    'is_finished'   => $isFinished ? 1 : 0,
                    'ft_home_score' => $ftHome,
                    'ft_away_score' => $ftAway,

                    'minute'        => $minute !== null ? (int)$minute : null,
                ]
            );
        }
    }
}
