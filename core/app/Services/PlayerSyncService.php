<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PlayerSyncService
{
    public function __construct(
        protected ?string $token = null
    ) {
        $this->token = $this->token ?: config('services.SPORTMONKS_TOKEN');
    }

    /**
     * مزامنة لاعبي فريق في موسم محدد
     */
    public function syncByTeam(int $teamId, int $seasonId): array
    {
        $baseUrl = "https://api.sportmonks.com/v3/football/teams/{$teamId}";

        $primaryResponse = Http::timeout(60)
            ->retry(3, 1000)
            ->get($baseUrl, [
                'api_token' => $this->token,
                'locale'    => 'ar',
                'include'   => 'players.player',
            ]);

        $englishResponse = Http::timeout(60)
            ->retry(3, 1000)
            ->get($baseUrl, [
                'api_token' => $this->token,
                'locale'    => 'en',
                'include'   => 'players.player',
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

        $primaryRows = collect(data_get($primaryResponse->json(), 'data.players', []));
        $englishRows = collect(data_get($englishResponse->json(), 'data.players', []));
        // dd($primaryRows);

        return $this->persistSquadRows(
            primaryRows: $primaryRows,
            englishRows: $englishRows,
            teamId: $teamId,
            seasonId: $seasonId
        );
    }

    /**
     * مزامنة لاعبين حسب الدولة
     */
    public function syncByCountry(int $countryId, ?int $limitPages = null): array
    {
        $page = 1;
        $saved = 0;
        $processed = 0;

        do {
            $primaryResponse = Http::timeout(60)
                ->retry(3, 1000)
                ->get('https://api.sportmonks.com/v3/football/players', [
                    'api_token' => $this->token,
                    'locale'    => 'ar',
                    'include'   => 'country;nationality;position;detailedPosition;teams;transfers',
                    'filters'   => "playerCountries:{$countryId}",
                    'page'      => $page,
                    'per_page'  => 50,
                    'order'     => 'asc',
                ]);

            $englishResponse = Http::timeout(60)
                ->retry(3, 1000)
                ->get('https://api.sportmonks.com/v3/football/players', [
                    'api_token' => $this->token,
                    'locale'    => 'en',
                    'include'   => 'country;nationality;position;detailedPosition;teams;transfers',
                    'filters'   => "playerCountries:{$countryId}",
                    'page'      => $page,
                    'per_page'  => 50,
                    'order'     => 'asc',
                ]);

            if (!$primaryResponse->successful()) {
                throw new \RuntimeException(
                    "SportMonks Arabic players by country request failed. Status: {$primaryResponse->status()} Body: {$primaryResponse->body()}"
                );
            }

            if (!$englishResponse->successful()) {
                throw new \RuntimeException(
                    "SportMonks English players by country request failed. Status: {$englishResponse->status()} Body: {$englishResponse->body()}"
                );
            }

            $primaryJson = $primaryResponse->json();
            $englishJson = $englishResponse->json();

            $primaryRows = collect(data_get($primaryJson, 'data', []));
            // dd($primaryRows);
            $englishRows = collect(data_get($englishJson, 'data', []));

            $englishMap = $englishRows->keyBy('id');

            DB::transaction(function () use ($primaryRows, $englishMap, &$saved, &$processed) {
                foreach ($primaryRows as $row) {
                    $processed++;

                    $playerId = (int) data_get($row, 'id', 0);
                    if ($playerId <= 0) {
                        continue;
                    }

                    $englishRow = $englishMap->get($playerId, []);
                    $teamsRaw = (array) data_get($row, 'teams', []);
                    if(count($teamsRaw) > 0){
                        $this->savePlayer($row, $englishRow);
                        foreach($teamsRaw as $team){
                            if(data_get($team, 'team_id') && data_get($team, 'player_id')){
                                DB::table('team_players')->updateOrInsert(
                                    [
                                        'team_id'   => data_get($team, 'team_id'),
                                        'player_id' => data_get($team, 'player_id'),
                                    ],
                                    [
                                        'position_id'          => data_get($team, 'position_id'),
                                        'detailed_position_id' => data_get($team, 'detailed_position_id'),
                                        'jersey_number'        => data_get($team, 'jersey_number'),
                                        'from_date'            => data_get($team, 'start'),
                                        'to_date'              => data_get($team, 'end'),
                                        'is_current'           => 1,
                                        'is_captain'           => (bool) data_get($team, 'captain', false),
                                        'updated_at'           => now(),
                                        'created_at'           => now(),
                                    ]
                                );
                            }
                        }
                        $saved++;
                    }
                }
            });

            $hasMore = (bool) data_get($primaryJson, 'pagination.has_more', false);
            $page++;

            if ($limitPages && $page > $limitPages) {
                $hasMore = false;
            }
        } while ($hasMore);

        return [
            'ok'        => true,
            'mode'      => 'country',
            'saved'     => $saved,
            'processed' => $processed,
            'countryId' => $countryId,
        ];
    }

    /**
     * حفظ سكواد الفريق + ربطه بجدول team_player
     */
    protected function persistSquadRows(
        Collection $primaryRows,
        Collection $englishRows,
        int $teamId,
        int $seasonId
    ): array {
        $saved = 0;
        $processed = 0;

        $englishMap = $englishRows->keyBy(function ($item) {
            return (int) (
                data_get($item, 'player.id')
                ?: data_get($item, 'player_id')
                ?: data_get($item, 'id')
                ?: 0
            );
        });

        DB::transaction(function () use ($primaryRows, $englishMap, $teamId, $seasonId, &$saved, &$processed) {
            foreach ($primaryRows as $row) {
                $processed++;

                $playerId = (int) (
                    data_get($row, 'player.id')
                    ?: data_get($row, 'player_id')
                    ?: data_get($row, 'id')
                    ?: 0
                );

                if ($playerId <= 0) {
                    continue;
                }

                $englishRow = $englishMap->get($playerId, []);

                // 1) احفظ اللاعب أولاً
                $playerModel = $this->savePlayer($row, $englishRow);

                // 2) تأكد أنه انحفظ فعلاً
                if (!Player::where('id', $playerModel->id)->exists()) {
                    throw new \RuntimeException("Player not saved: {$playerModel->id}");
                }

                // 3) احفظ الربط مباشرة في pivot
                if ($playerModel->id && $teamId) {
                    DB::table('team_players')->updateOrInsert(
                        [
                            'team_id'   => $teamId,
                            'player_id' => $playerModel->id,
                        ],
                        [
                            'position_id'          => data_get($row, 'player.position_id') ?: data_get($row, 'position_id'),
                            'detailed_position_id' => data_get($row, 'player.detailed_position_id') ?: data_get($row, 'detailed_position_id'),
                            'jersey_number'        => data_get($row, 'jersey_number'),
                            'from_date'            => data_get($row, 'from_date'),
                            'to_date'              => data_get($row, 'to_date'),
                            'is_current'           => 1,
                            'is_captain'           => (bool) data_get($row, 'captain', false),
                            'updated_at'           => now(),
                            'created_at'           => now(),
                        ]
                    );
                }

                $saved++;
            }
        });

        return [
            'ok'        => true,
            'mode'      => 'team',
            'saved'     => $saved,
            'processed' => $processed,
            'teamId'    => $teamId,
            'seasonId'  => $seasonId,
        ];
    }

    /**
     * حفظ اللاعب نفسه
     */
    protected function savePlayer(array $row, array $englishRow = []): Player
    {
        $playerData = data_get($row, 'player', []);
        $englishPlayerData = data_get($englishRow, 'player', []);

        $playerId = (int) (
            data_get($playerData, 'id')
            ?: data_get($row, 'player_id')
            ?: data_get($row, 'id')
            ?: 0
        );

        if ($playerId <= 0) {
            throw new \RuntimeException('Invalid player id.');
        }

        Player::updateOrCreate(
            ['id' => $playerId],
            [
                'name_ar'              => $this->resolveText(
                    data_get($playerData, 'name') ?: data_get($row, 'name')
                ),
                'name_en'              => $this->resolveText(
                    data_get($englishPlayerData, 'name') ?: data_get($englishRow, 'name'),
                    'en'
                ),
                'common_name'            => $this->resolveText(
                    data_get($playerData, 'common_name') ?: data_get($row, 'common_name')
                ),
                'image_path'           => data_get($playerData, 'image_path') ?: data_get($row, 'image_path'),
                'date_of_birth'        => data_get($playerData, 'date_of_birth') ?: data_get($row, 'date_of_birth'),
                'gender'               => data_get($playerData, 'gender') ?: data_get($row, 'gender'),
                'height'               => data_get($playerData, 'height') ?: data_get($row, 'height'),
                'weight'               => data_get($playerData, 'weight') ?: data_get($row, 'weight'),
                'country_id'           => data_get($playerData, 'country_id') ?: data_get($row, 'country_id'),
                'nationality_id'       => data_get($playerData, 'nationality_id') ?: data_get($row, 'nationality_id'),
                'position_id'          => data_get($playerData, 'position_id') ?: data_get($row, 'position_id'),
                'detailed_position_id' => data_get($playerData, 'detailed_position_id') ?: data_get($row, 'detailed_position_id'),
                'foot'                 => data_get($playerData, 'foot') ?: data_get($row, 'foot'),
                'sport_id'             => data_get($playerData, 'sport_id') ?: data_get($row, 'sport_id'),
            ]
        );

        return Player::findOrFail($playerId);
    }

    protected function resolveText(mixed $value, string $locale = 'ar'): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        if (is_array($value)) {
            return $value[$locale]
                ?? $value['en']
                ?? $value['ar']
                ?? collect($value)->first(fn($v) => is_string($v) || is_numeric($v));
        }

        return null;
    }
}
