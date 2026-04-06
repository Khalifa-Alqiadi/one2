<?php

namespace App\Services;

use App\Models\Standing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SportmonksStandingService
{

    public function syncSeasonStandings(int $seasonId, ?string $locale = null): array
    {
        $token = config('services.SPORTMONKS_TOKEN');
        $locale = $locale ?: 'ar';

        $url = "https://api.sportmonks.com/v3/football/standings/seasons/{$seasonId}";

        $response = Http::timeout(30)
            ->get($url, [
                'api_token' => $token,
                'locale'    => $locale,
                'include'   => 'participant;details.type;form',
            ]);

        if (!$response->successful()) {
            return [
                'ok'      => false,
                'message' => 'فشل الاتصال بـ SportMonks standings endpoint',
                'status'  => $response->status(),
                'data'    => [],
            ];
        }

        $json = $response->json();
        $rows = data_get($json, 'data', []);

        if (!is_array($rows)) {
            return [
                'ok'      => false,
                'message' => 'صيغة الرد غير متوقعة',
                'status'  => $response->status(),
                'data'    => [],
            ];
        }

        $normalized = collect($rows)
            ->filter(fn($row) => is_array($row))
            ->map(fn($row) => $this->normalizeStandingRow($row, $seasonId))
            ->values()
            ->all();

        DB::transaction(function () use ($normalized) {
            foreach ($normalized as $row) {
                Standing::updateOrCreate(
                    [
                        'season_id'      => $row['season_id'],
                        'stage_id'       => $row['stage_id'],
                        'round_id'       => $row['round_id'],
                        'participant_id' => $row['participant_id'],
                        'group_name'     => $row['group_name'],
                    ],
                    $row
                );
            }
        });

        return [
            'ok'      => true,
            'message' => 'تمت مزامنة جدول الترتيب بنجاح',
            'count'   => count($normalized),
            'data'    => $normalized,
        ];
    }

    private function normalizeStandingRow(array $row, int $seasonId): array
    {
        $details = collect(data_get($row, 'details', []))
            ->filter(fn($x) => is_array($x))
            ->keyBy(function ($x) {
                return strtolower((string) (
                    data_get($x, 'type.code')
                    ?? data_get($x, 'type.developer_name')
                    ?? data_get($x, 'type.name')
                    ?? data_get($x, 'type')
                    ?? ''
                ));
            });

        $getDetail = function (array $keys, $default = 0) use ($details) {
            foreach ($keys as $key) {
                $item = $details->get(strtolower($key));

                if (!$item) {
                    continue;
                }

                $value = data_get($item, 'value');

                if (is_numeric($value)) {
                    return (int) $value;
                }

                if (is_string($value) && trim($value) !== '') {
                    return $value;
                }
            }

            return $default;
        };

        $participantId = (int) (
            data_get($row, 'participant_id')
            ?? data_get($row, 'participant.id')
            ?? 0
        );

        $position = (int) (
            data_get($row, 'position')
            ?? data_get($row, 'rank')
            ?? 0
        );

        $points = (int) (
            data_get($row, 'points')
            ?? $getDetail(['points', 'total-points'], 0)
        );

        $played = (int) (
            data_get($row, 'played')
            ?? data_get($row, 'games_played')
            ?? data_get($row, 'matches_played')
            ?? $getDetail([
                'overall-games-played',
                'overall-matches-played',
                'overall-played',
                'games-played',
                'matches-played',
                'played',
            ], 0)
        );

        $won = (int) (
            data_get($row, 'won')
            ?? data_get($row, 'wins')
            ?? $getDetail([
                'overall-won',
                'overall-wins',
                'won',
                'wins',
            ], 0)
        );

        $draw = (int) (
            data_get($row, 'draw')
            ?? data_get($row, 'drawn')
            ?? data_get($row, 'draws')
            ?? $getDetail([
                'overall-draw',
                'overall-draws',
                'draw',
                'draws',
            ], 0)
        );

        $lost = (int) (
            data_get($row, 'lost')
            ?? data_get($row, 'losses')
            ?? $getDetail([
                'overall-lost',
                'overall-losses',
                'lost',
                'losses',
            ], 0)
        );

        if ($played === 0 && ($won + $draw + $lost) > 0) {
            $played = $won + $draw + $lost;
        }

        $goalsFor = (int) (
            data_get($row, 'scores_for')
            ?? data_get($row, 'goals_for')
            ?? data_get($row, 'gf')
            ?? $getDetail([
                'overall-goals-for',
                'goals-for',
                'goals_for',
                'gf',
            ], 0)
        );

        $goalsAgainst = (int) (
            data_get($row, 'scores_against')
            ?? data_get($row, 'goals_against')
            ?? data_get($row, 'ga')
            ?? $getDetail([
                'overall-goals-against',
                'goals-against',
                'goals_against',
                'ga',
            ], 0)
        );

        $goalDifference = (int) (
            data_get($row, 'goal_difference')
            ?? data_get($row, 'gd')
            ?? $getDetail([
                'overall-goals-difference',
                'goals-difference',
                'goal_difference',
                'gd',
            ], ($goalsFor - $goalsAgainst))
        );

        $formCollection = collect(data_get($row, 'form', []))
            ->filter(fn($x) => is_array($x))
            ->sortBy(function ($x) {
                return (int) data_get($x, 'sort_order', 0);
            })
            ->pluck('form')
            ->filter()
            ->take(5)
            ->values();

        $formString = $formCollection->implode(',');

        return [
            'league_id'              => data_get($row, 'league_id'),
            'season_id'              => $seasonId,
            'stage_id'               => data_get($row, 'stage_id'),
            'round_id'               => data_get($row, 'round_id'),
            'team_id'                => $participantId,
            'sportmonks_standing_id' => data_get($row, 'id'),
            'participant_id'         => $participantId,
            'group_name'             => (string) (
                data_get($row, 'group.name')
                ?? data_get($row, 'group_name')
                ?? ''
            ),
            'standing_type'          => (string) (
                data_get($row, 'type')
                ?? data_get($row, 'standing_type')
                ?? 'overall'
            ),
            'position'               => $position,
            'points'                 => $points,
            'played'                 => $played,
            'won'                    => $won,
            'draw'                   => $draw,
            'lost'                   => $lost,
            'goals_for'              => $goalsFor,
            'goals_against'          => $goalsAgainst,
            'goal_difference'        => $goalDifference,
            'recent_form_points'     => null,
            'form'                   => $formString,
            'payload_json'           => $row,
            'synced_at'              => now(),
        ];
    }

    public function refreshStandingsCache(int $seasonId, string $locale): void
    {
        $cacheKey = "sm:standings:season:{$seasonId}:{$locale}";
        $metaKey  = "sm:standings:season:{$seasonId}:{$locale}:meta";

        $hasStandings = Standing::where('season_id', $seasonId)->exists();

        Cache::forget($cacheKey);
        Cache::forget($metaKey);

        if (!$hasStandings) {
            $this->syncSeasonStandings($seasonId, $locale);
        }
    }

    public function getStandingsCached(int $seasonId, string $locale): array
    {
        $cacheKey = "sm:standings:season:{$seasonId}:{$locale}";
        $metaKey  = "sm:standings:season:{$seasonId}:{$locale}:meta";


        $standings = Cache::remember($cacheKey, 3600, function () use ($seasonId, $metaKey) {
            $rows = Standing::query()
                ->where('season_id', $seasonId)
                ->orderByRaw("CASE WHEN group_name IS NULL OR group_name = '' THEN 1 ELSE 0 END")
                ->orderBy('group_name')
                ->orderBy('position')
                ->get();

            $payload = $rows
                ->map(function ($row) {
                    return is_array($row->payload_json) ? $row->payload_json : [];
                })
                ->filter(fn($row) => !empty($row))
                ->values()
                ->all();

            $lastSyncedAt = optional(
                $rows->sortByDesc('synced_at')->first()
            )->synced_at;

            Cache::put($metaKey, [
                'fetched_at' => $lastSyncedAt
                    ? $lastSyncedAt->toDateTimeString()
                    : now()->toDateTimeString(),
            ], 3600);

            return $payload;
        });

        $meta = Cache::get($metaKey, []);

        return [
            'ok' => true,
            'error' => null,
            'standings' => $standings,
            'fetched_at' => data_get($meta, 'fetched_at'),
        ];
    }
}
