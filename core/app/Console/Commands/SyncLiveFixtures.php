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

        $query = Fixture::query()
            ->when($leagueId, fn($q) => $q->where('league_id', $leagueId))
            ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
            ->where(function ($q) {
                $q->where('is_finished', 0)
                  ->orWhere(function ($q2) {
                      $q2->whereNotNull('starting_at')
                         ->whereBetween('starting_at', [now()->subHours(4), now()->addHours(1)]);
                  });
            })
            ->select([
                'id', 'league_id', 'season_id', 'round_id', 'stage_id',
                'starting_at', 'is_finished', 'home_score', 'away_score',
                'state_code', 'state_name', 'minute',
            ]);

        $fixtures = $query->get();

        $updated = 0;

        foreach ($fixtures as $fx) {
            $data = app(LiveMatchesService::class)
                ->fetchFixtureLiveFromSportmonks($fx->id, $token, $locale);

            if (!$data) {
                continue;
            }

            // لا تحدث منتهية إلا إذا API أكد FT
            if (($data['status'] ?? '') === 'FT') {
                $fx->update([
                    'home_score'    => $data['home_score'],
                    'away_score'    => $data['away_score'],
                    'ft_home_score' => $data['home_score'],
                    'ft_away_score' => $data['away_score'],
                    'is_finished'   => 1,
                    'state_code'    => $data['state_code'] ?? 'FT',
                    'state_name'    => $data['state_name'] ?? 'Finished',
                    'minute'        => null,
                ]);

                Cache::forget("sportmonks:fixture_live:{$fx->id}:{$locale}");
                $updated++;
                continue;
            }

            // LIVE / HT / NS
            $fx->update([
                'home_score'  => $data['home_score'],
                'away_score'  => $data['away_score'],
                'state_code'  => $data['state_code'] ?? $fx->state_code,
                'state_name'  => $data['state_name'] ?? $fx->state_name,
                'minute'      => (($data['status'] ?? '') === 'LIVE') ? $data['minute'] : null,
                'is_finished' => 0,
            ]);

            Cache::forget("sportmonks:fixture_live:{$fx->id}:{$locale}");
            $updated++;
        }

        $this->info("Fixtures synced: {$updated}");

        return self::SUCCESS;
    }
}
