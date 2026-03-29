<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Services\LiveMatchesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncLiveFixtures extends Command
{
    protected $signature = 'fixtures:sync-live {--league_id=} {--season_id=}';
    protected $description = 'Sync live / near-live fixtures from SportMonks and update DB';

    public function handle(): int
    {
        $leagueId = $this->option('league_id');
        $seasonId = $this->option('season_id');
        $token    = config('services.SPORTMONKS_TOKEN');
        $locale   = app()->getLocale() ?: 'ar';

        $updated = 0;

        Fixture::query()
            ->when($leagueId, fn($q) => $q->where('league_id', $leagueId))
            ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
            ->where(function ($q) {
                $q->where('is_finished', 0)
                    ->whereNotNull('starting_at')
                    ->where('starting_at', '<=', now());
            })
            ->select([
                'id',
                'league_id',
                'season_id',
                'round_id',
                'stage_id',
                'starting_at',
                'is_finished',
                'home_score',
                'away_score',
                'ft_home_score',
                'ft_away_score',
                'state_code',
                'state_name',
                'minute',
            ])
            ->chunkById(100, function ($fixtures) use ($token, $locale, &$updated) {
                foreach ($fixtures as $fx) {
                    $data = app(LiveMatchesService::class)
                        ->fetchFixtureLiveFromSportmonks($fx->id, $token, $locale);

                    if (!$data) {
                        continue;
                    }

                    $stateCode = $data['state_code'] ?? $fx->state_code;
                    $stateName = $data['state_name'] ?? $fx->state_name;
                    $status    = $data['status'] ?? null;
                    $homeScore = $data['home_score'] ?? $fx->home_score;
                    $awayScore = $data['away_score'] ?? $fx->away_score;
                    $minute    = $data['minute'] ?? null;

                    $isFinalState = in_array($stateCode, ['FT', 'AET', 'PEN', 'CANC', 'POSTP'], true);

                    if ($isFinalState) {
                        $payload = [
                            'home_score'    => $homeScore,
                            'away_score'    => $awayScore,
                            'ft_home_score' => $homeScore,
                            'ft_away_score' => $awayScore,
                            'is_finished'   => 1,
                            'state_code'    => $stateCode,
                            'state_name'    => $stateName,
                            'minute'        => null,
                        ];

                        if ($this->hasChanges($fx, $payload)) {
                            $fx->update($payload);
                            Cache::forget("sportmonks:fixture_live:{$fx->id}:{$locale}");
                            $updated++;
                        }

                        continue;
                    }

                    $payload = [
                        'home_score'  => $homeScore,
                        'away_score'  => $awayScore,
                        'state_code'  => $stateCode,
                        'state_name'  => $stateName,
                        'minute'      => $status === 'LIVE' ? $minute : null,
                        'is_finished' => 0,
                    ];

                    if ($this->hasChanges($fx, $payload)) {
                        $fx->update($payload);
                        Cache::forget("sportmonks:fixture_live:{$fx->id}:{$locale}");
                        $updated++;
                    }
                }
            });

        $this->info("Fixtures synced: {$updated}");

        return self::SUCCESS;
    }

    /**
     * تحقق هل فعلاً في بيانات تغيرت قبل update
     */
    private function hasChanges(Fixture $fixture, array $payload): bool
    {
        foreach ($payload as $key => $value) {
            if ($fixture->{$key} != $value) {
                return true;
            }
        }

        return false;
    }
}
