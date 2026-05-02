<?php

namespace App\Services;

use App\Models\Fixture;
use Illuminate\Support\Facades\Artisan;

class FetchFixtureDetailsFromSportmonksService
{
    private ApiClientService $apiClient;
    private LiveMatchesService $handleMatchesService;

    public function __construct(ApiClientService $apiClient, LiveMatchesService $handleMatchesService)
    {
        $this->apiClient = $apiClient;
        $this->handleMatchesService = $handleMatchesService;
    }

    private function normalizeNews(array $rows): array
    {
        return collect($rows)
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) {
                return [
                    'id' => data_get($row, 'id'),
                    'title' => (string) (data_get($row, 'title') ?? ''),
                    'short_title' => (string) (data_get($row, 'short_title') ?? ''),
                    'image' => (string) (
                        data_get($row, 'image_path')
                        ?? data_get($row, 'image')
                        ?? ''
                    ),
                    'url' => (string) (data_get($row, 'url') ?? ''),
                    'published_at' => data_get($row, 'published_at'),
                ];
            })
            ->values()
            ->all();
    }

    public function fetchFixtureDetailsFromSportmonks(int $fixtureId, string $token, string $locale): ?array
    {
        $url = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=state;participants;scores;events;events.player;events.type;events.relatedPlayer;lineups;lineups.player;lineups.position;statistics.type;metadata;odds;periods;tvStations;tvStations.country;tvStations.tvStation;sidelined;sidelined.type;sidelined.player;sidelined.sideline;tvstations;venue";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $match = data_get($res, 'json.data', []);
        if (!$match) return null;

        $fixture = Fixture::with(['league', 'homeTeam', 'awayTeam'])->find($fixtureId);

        $participants = collect(data_get($match, 'participants', []));
        $home = $participants->first(fn($p) => data_get($p, 'meta.location') === 'home');
        $away = $participants->first(fn($p) => data_get($p, 'meta.location') === 'away');

        $homeId = (int) $this->safeTeamId($home);
        $awayId = (int) $this->safeTeamId($away);

        $scoresArr = data_get($match, 'scores', []) ?? [];
        [$homeScore, $awayScore] = $this->handleMatchesService->extractGoalsFromScores($scoresArr);



        $stateName = (string) data_get($match, 'state.name', '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name');



        $stateCode = $this->handleMatchesService->normalizeStateCode($rawCode, $stateName);

        $resultInfo = (string) data_get($match, 'result_info', '');
        $isFinished = $this->handleMatchesService->isFinishedState($stateCode, $stateName, $resultInfo);
        $isHalf     = $this->handleMatchesService->isHalfTimeState($stateCode, $stateName);
        $isLive     = $this->handleMatchesService->isLiveState($stateCode, $stateName);

        $status = 'NS';
        if ($isFinished) $status = 'FT';
        elseif ($isHalf) $status = 'HT';
        elseif ($isLive) $status = 'LIVE';
        elseif ($stateCode === "INPLAY_2ND") $status = 'LIVE';
        elseif ($stateCode === "INPLAY_1ST") $status = 'LIVE';

        $minute =
            data_get($match, 'time.minute')
            ?? data_get($match, 'time.current_minute')
            ?? data_get($match, 'time.added_time')
            ?? null;

        if (!is_numeric($minute)) {
            $minute = $this->handleMatchesService->extractMinuteFromPeriods((array) data_get($match, 'periods', []));
        }

        if (!is_numeric($minute)) {
            $minute = $this->handleMatchesService->extractMinuteFromEvents((array) data_get($match, 'events', []));
        }

        $minute = is_numeric($minute) ? (int) $minute : null;

        $eventsRaw = (array) data_get($match, 'events', []);
        $events = $this->handleMatchesService->normalizeEvents($eventsRaw, $homeId, $awayId);

        // $minute = data_get($match, 'time.minute') ?? data_get($match, 'time.current_minute');
        // $minute = is_numeric($minute) ? (int)$minute : null;

        // if ($minute === null) {
        // $minute = collect($eventsRaw)->pluck('minute')->filter()->max();
        // $minute = is_numeric($minute) ? (int)$minute : null;
        // }

        $lineups = collect(data_get($match, 'lineups', []))->values()->all();

        $statsRaw = data_get($match, 'statistics', []);
        $statsRaw = is_array($statsRaw) ? $statsRaw : [];
        if (isset($statsRaw['data']) && is_array($statsRaw['data'])) {
            $statsRaw = $statsRaw['data'];
        }
        $statsRows = $this->normalizeStats($statsRaw, $homeId, $awayId, $home, $away);

        // ✅ probabilities (لو عندك add-on سيطلع، غير كذا null)
        $probabilities = null;

        $tvStationsRaw = data_get($match, 'tvstations', []);
        $tvStationsRaw = is_array($tvStationsRaw) ? $tvStationsRaw : [];
        if (isset($tvStationsRaw['data']) && is_array($tvStationsRaw['data'])) {
            $tvStationsRaw = $tvStationsRaw['data'];
        }
        $tvStations = $this->normalizeTvStations($tvStationsRaw);

        // $sidelinedRaw = data_get($match, 'sidelined', []);
        // if (isset($sidelinedRaw['data']) && is_array($sidelinedRaw['data'])) {
        //     $sidelinedRaw = $sidelinedRaw['data'];
        // }
        // $sidelinedRaw = is_array($sidelinedRaw) ? $sidelinedRaw : [];
        //         $injuries = collect($sidelinedRaw)->filter(function ($row) {
        //             return mb_strtolower((string) (
        //                 data_get($row, 'type.name')
        //                 ?? data_get($row, 'sideline.category')
        //                 ?? ''
        //             ));
        //         })->values()->all();
        //
        //         $suspensions = collect($sidelinedRaw)->filter(function ($row) {
        //             $text = mb_strtolower((string) (
        //                 data_get($row, 'type.name')
        //                 ?? data_get($row, 'category')
        //                 ?? data_get($row, 'sideline.category')
        //                 ?? data_get($row, 'sideline.type')
        //                 ?? ''
        //             ));
        //
        //             return str_contains($text, 'suspend')
        //                 || str_contains($text, 'card')
        //                 || str_contains($text, 'red');
        //         })->values()->all();

        $sidelinedRaw = data_get($match, 'sidelined', []);
        if (isset($sidelinedRaw['data']) && is_array($sidelinedRaw['data'])) {
            $sidelinedRaw = $sidelinedRaw['data'];
        }
        $sidelinedRaw = is_array($sidelinedRaw) ? $sidelinedRaw : [];

        $normalizedSidelined = $this->normalizeSidelined($sidelinedRaw);

        $injuries = $this->extractInjuriesFromSidelined($normalizedSidelined);
        $suspensions = $this->extractSuspensionsFromSidelined($normalizedSidelined);

        $venue = data_get($match, 'venue', []);

        $venueData = [
            'id' => (int) data_get($venue, 'id', 0),
            'name' => (string) data_get($venue, 'name', ''),
            'city' => (string) data_get($venue, 'city_name', ''),
            'capacity' => (int) data_get($venue, 'capacity', 0),
            'image' => (string) data_get($venue, 'image_path', ''),
        ];

        $odds = data_get($match, 'odds', []);
        $odds = is_array($odds) ? $odds : [];

        $ox = $this->extractOdds1X2FromFixtureOdds($odds);

        if ($ox) {
            $probabilities = $this->oddsToProbabilitiesFromValues(
                (float)($ox['home_value'] ?? 0),
                (float)($ox['draw_value'] ?? 0),
                (float)($ox['away_value'] ?? 0),
            );
        }


        return [
            'id' => (int) data_get($match, 'id'),
            'starting_at' => data_get($match, 'starting_at'),
            'status' => $status,
            'state_code' => $stateCode,
            'state_name' => $stateName,
            'is_finished' => $isFinished,
            'minute' =>  $minute,

            'home' => [
                'id' => $homeId,
                'name' => data_get($home, 'name', ''),
                'logo' => data_get($home, 'image_path', ''),
            ],
            'away' => [
                'id' => $awayId,
                'name' => data_get($away, 'name', ''),
                'logo' => data_get($away, 'image_path', ''),
            ],

            'score' => [
                'home' => $homeScore,
                'away' => $awayScore,
            ],

            'events' => $events,
            'fixture' => $fixture ?? null,
            'locale' => $locale ?? 'ar',
            'lineups' => $lineups,
            'statistics_rows' => $statsRows,

            'probabilities' => $probabilities,
            'predictable'   => data_get($match, 'metadata.predictable'),

            'tv_stations' => $tvStations,
            'injuries' => $injuries,
            'suspensions' => $suspensions,
            'venue_id' => (int) data_get($venue, 'id', 0) ?: null,
            'venue' => $venueData,
        ];
    }

    private function safeTeamId($participant)
    {
        return data_get($participant, 'id') ?: data_get($participant, 'participant_id');
    }

    private function extractOdds1X2FromFixtureOdds(array $odds): ?array
    {
        $odds = collect($odds)->filter(fn($o) => is_array($o))->values();

        // normalize outcome label to one of: home/draw/away
        $norm = function ($o): ?string {
            $label = strtoupper(trim((string)(
                data_get($o, 'label')
                ?? data_get($o, 'name')
                ?? data_get($o, 'outcome')
                ?? data_get($o, 'selection')
                ?? data_get($o, 'type')
                ?? ''
            )));

            // common variants
            if (in_array($label, ['1', 'HOME', 'TEAM1', 'H'], true)) return 'home';
            if (in_array($label, ['X', 'DRAW', 'TIE', 'D'], true)) return 'draw';
            if (in_array($label, ['2', 'AWAY', 'TEAM2', 'A'], true)) return 'away';

            return null;
        };

        // extract numeric odd value
        $oddValue = function ($o): ?float {
            $v = data_get($o, 'value')
                ?? data_get($o, 'odd')
                ?? data_get($o, 'odds')
                ?? data_get($o, 'rate')
                ?? null;

            return is_numeric($v) ? (float)$v : null;
        };

        // keep only odds that look like 1/X/2 outcomes
        $candidates = $odds
            ->map(function ($o) use ($norm, $oddValue) {
                $k = $norm($o);
                $v = $oddValue($o);
                if (!$k || !$v) return null;

                return [
                    '_k' => $k,               // home/draw/away
                    '_v' => $v,               // numeric odd
                    '_market_id' => (int)(data_get($o, 'market_id') ?? data_get($o, 'market.id') ?? 0),
                    '_bookmaker_id' => (int)(data_get($o, 'bookmaker_id') ?? data_get($o, 'bookmaker.id') ?? 0),
                    '_ts' => (int)(data_get($o, 'latest_bookmaker_update') ?? data_get($o, 'updated_at_timestamp') ?? 0),
                    '_raw' => $o,
                ];
            })
            ->filter()
            ->values();

        if ($candidates->isEmpty()) return null;

        // group by market+bookmaker, pick any group that has home/draw/away
        $groups = $candidates->groupBy(function ($x) {
            return $x['_market_id'] . ':' . $x['_bookmaker_id'];
        });

        $best = null;
        $bestTs = -1;

        foreach ($groups as $key => $items) {
            $keys = $items->pluck('_k')->unique()->values()->all();
            if (!in_array('home', $keys, true) || !in_array('draw', $keys, true) || !in_array('away', $keys, true)) {
                continue;
            }

            // pick group with newest timestamp
            $ts = (int) $items->max('_ts');
            if ($ts > $bestTs) {
                $bestTs = $ts;
                $best = $items;
            }
        }

        if (!$best) return null;

        $home = $best->firstWhere('_k', 'home');
        $draw = $best->firstWhere('_k', 'draw');
        $away = $best->firstWhere('_k', 'away');

        if (!$home || !$draw || !$away) return null;

        return [
            'home_value' => (float) $home['_v'],
            'draw_value' => (float) $draw['_v'],
            'away_value' => (float) $away['_v'],
            'meta' => [
                'market_id' => (int) $home['_market_id'],
                'bookmaker_id' => (int) $home['_bookmaker_id'],
                'timestamp' => (int) $bestTs,
            ],
        ];
    }

    private function oddsToProbabilitiesFromValues(float $homeOdd, float $drawOdd, float $awayOdd): ?array
    {
        if ($homeOdd <= 0 || $drawOdd <= 0 || $awayOdd <= 0) return null;

        $ih = 1 / $homeOdd;
        $id = 1 / $drawOdd;
        $ia = 1 / $awayOdd;
        $sum = $ih + $id + $ia;
        if ($sum <= 0) return null;

        $home = (int) round(($ih / $sum) * 100, 0);
        $draw = (int) round(($id / $sum) * 100, 0);
        $away = max(0, 100 - $home - $draw);

        return ['home' => $home, 'draw' => $draw, 'away' => (int)$away];
    }

    private function normalizeTvStations(array $rows): array
    {
        return collect($rows)
            ->filter(fn($x) => is_array($x))
            ->map(function ($row) {
                return [
                    'id'      => (int) data_get($row, 'id', 0),
                    'name'    => (string) (
                        data_get($row, 'tvstation.name')
                        ?? data_get($row, 'data.tvstation.name')
                        ?? ''
                    ),
                    'image'   => (string) (
                        data_get($row, 'tvstation.image_path')
                        ?? data_get($row, 'data.tvstation.image_path')
                        ?? data_get($row, 'logo')
                        ?? ''
                    ),
                    'country' => (string) (
                        data_get($row, 'country.name')
                        ?? data_get($row, 'country')
                        ?? ''
                    ),
                    'url'     => (string) (
                        data_get($row, 'tvstation.url')
                        ?? data_get($row, 'data.tvstation.url')
                        ?? ''
                    ),
                ];
            })
            ->filter(fn($x) => $x['id'] || $x['name'] !== '')
            ->values()
            ->all();
    }

    private function normalizeStats(array $stats, int $homeId, int $awayId, $homeParticipant, $awayParticipant): array
    {
        $stats = collect($stats)->filter(fn($x) => is_array($x))->values();

        $homeAlt = [
            (int) data_get($homeParticipant, 'id', 0),
            (int) data_get($homeParticipant, 'participant_id', 0),
        ];
        $awayAlt = [
            (int) data_get($awayParticipant, 'id', 0),
            (int) data_get($awayParticipant, 'participant_id', 0),
        ];

        $pickValue = function ($row) {
            return data_get($row, 'data.value')
                ?? data_get($row, 'value')
                ?? data_get($row, 'data');
        };

        $format = function ($v) {
            if ($v === null || $v === '') return '-';
            if (is_string($v) && str_contains($v, '%')) return $v;
            if (is_numeric($v)) {
                $n = (float)$v;
                if ($n > 0 && $n < 1) return round($n * 100) . '%';
                return (string)((int)$n);
            }
            return (string)$v;
        };

        // ✅ label: type.name -> type_id -> fallback
        $grouped = $stats->groupBy(function ($s) {
            $name = data_get($s, 'type.name')
                ?? data_get($s, 'type.data.name')
                ?? data_get($s, 'type')
                ?? data_get($s, 'name');

            if (is_string($name) && trim($name) !== '') {
                return trim($name);
            }

            $typeId = data_get($s, 'type_id');
            if (is_numeric($typeId)) {
                return 'type_id:' . (int)$typeId; // ✅ ما عاد Unknown
            }

            return 'Unknown';
        });

        $rows = [];

        foreach ($grouped as $label => $items) {
            $label = trim((string)$label);
            if ($label === '') continue;

            $homeRow = $items->first(fn($r) => in_array((int) data_get($r, 'participant_id', 0), $homeAlt, true));
            $awayRow = $items->first(fn($r) => in_array((int) data_get($r, 'participant_id', 0), $awayAlt, true));

            $hv = $homeRow ? $pickValue($homeRow) : null;
            $av = $awayRow ? $pickValue($awayRow) : null;

            // ✅ لو ما لقينا home/away بالمطابقة، خذ أول 2 كـ fallback
            if ($homeRow === null && $awayRow === null) {
                $items = $items->values();
                $hv = $pickValue($items->get(0));
                $av = $pickValue($items->get(1));
            }

            $rows[] = [
                'label' => $label,
                'home'  => $format($hv),
                'away'  => $format($av),
            ];
        }

        // ✅ إذا label type_id:123، نقدر نحوله لاسم عربي لاحقاً (اختياري)
        return collect($rows)->values()->all();
    }

    public function persistFixtureDetails(Fixture $fixture, array $data, bool $advanceStructure = true)
    {
        $wasFinished = (bool) $fixture->is_finished;
        $stateCode = strtoupper((string) data_get($data, 'state_code', ''));
        $status = strtoupper((string) data_get($data, 'status', ''));
        $isFinished = (bool) data_get($data, 'is_finished', false)
            || $status === 'FT'
            || in_array($stateCode, ['FT', 'CANC', 'ABD', 'SUSP'], true);

        $fixture->update([
            'state_code'            => data_get($data, 'state_code', ''),
            'state_name'            => data_get($data, 'state_name', ''),
            'events_json'            => data_get($data, 'events', []),
            'statistics_json'        => data_get($data, 'statistics_rows', []),
            'lineups_json'           => data_get($data, 'lineups', []),
            'win_probabilities_json' => data_get($data, 'probabilities'),
            'tv_stations_json'       => data_get($data, 'tv_stations', []),
            'injuries_json'          => data_get($data, 'injuries', []),
            'suspensions_json'       => data_get($data, 'suspensions', []),
            'venue_json'             => data_get($data, 'venue', []),
            'details_synced_at'      => now(),
            'home_score'             => data_get($data, 'score.home'),
            'away_score'             => data_get($data, 'score.away'),
            'venue_id'               => data_get($data, 'venue_id', data_get($data, 'venue.id')),
            'minute'                 => data_get($data, 'minute'),
            'is_finished'            => $isFinished ? 1 : 0,
        ]);

        if ($advanceStructure && !$wasFinished && $isFinished) {
            Artisan::call('football:advance-season-structure', [
                '--season_id' => $fixture->season_id,
                '--league_id' => $fixture->league_id,
                '--refresh-fixtures' => 0,
            ]);
        }

        return $fixture->fresh();
    }


    private function normalizeSidelined(array $rows): array
    {
        return collect($rows)
            ->filter(fn($row) => is_array($row))
            ->map(function ($row) {
                return [
                    'id' => (int) data_get($row, 'id', 0),
                    'fixture_id' => (int) data_get($row, 'fixture_id', 0),
                    'participant_id' => (int) data_get($row, 'participant_id', 0),
                    'player_id' => (int) data_get($row, 'player_id', 0),
                    'type_id' => (int) data_get($row, 'type_id', 0),

                    'type_name' => (string) data_get($row, 'type.name', ''),
                    'type_code' => (string) data_get($row, 'type.code', ''),
                    'developer_name' => (string) data_get($row, 'type.developer_name', ''),
                    'model_type' => (string) data_get($row, 'type.model_type', ''),

                    'player_name' => (string) (
                        data_get($row, 'player.display_name')
                        ?? data_get($row, 'player.name')
                        ?? ''
                    ),
                    'player_image' => (string) data_get($row, 'player.image_path', ''),

                    'sideline' => data_get($row, 'sideline'),
                    'raw' => $row,
                ];
            })
            ->values()
            ->all();
    }
    private function extractInjuriesFromSidelined(array $rows): array
    {
        return collect($rows)
            ->filter(function ($row) {
                $typeCode = mb_strtolower((string) data_get($row, 'type_code', ''));
                $devName  = mb_strtolower((string) data_get($row, 'developer_name', ''));
                $typeName = mb_strtolower((string) data_get($row, 'type_name', ''));

                return str_contains($typeCode, 'injury')
                    || str_contains($devName, 'injury')
                    || str_contains($typeName, 'إصابة')
                    || str_contains($typeCode, 'hamstring')
                    || str_contains($typeCode, 'knee')
                    || str_contains($typeCode, 'ankle')
                    || str_contains($typeCode, 'muscle');
            })
            ->values()
            ->all();
    }
    private function extractSuspensionsFromSidelined(array $rows): array
    {
        return collect($rows)
            ->filter(function ($row) {
                $typeCode = mb_strtolower((string) data_get($row, 'type_code', ''));
                $devName  = mb_strtolower((string) data_get($row, 'developer_name', ''));
                $typeName = mb_strtolower((string) data_get($row, 'type_name', ''));

                return str_contains($typeCode, 'suspension')
                    || str_contains($devName, 'suspension')
                    || str_contains($typeName, 'إيقاف')
                    || str_contains($typeCode, 'yellow-card')
                    || str_contains($typeCode, 'red-card')
                    || str_contains($devName, 'yellow_card')
                    || str_contains($devName, 'red_card');
            })
            ->values()
            ->all();
    }
}
