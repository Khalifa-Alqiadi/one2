<?php

namespace App\Console\Commands;

use App\Models\League;
use App\Models\Season;
use App\Services\SportmonksStandingService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateStandings extends Command
{
    protected $signature = 'standings:update
        {--season_id= : SportMonks season ID}
        {--league_id= : Limit update to one league}
        {--locale=ar : SportMonks locale}
        {--all : Update all saved seasons instead of current seasons only}';

    protected $description = 'Update football standings from SportMonks';

    public function handle(SportmonksStandingService $standings): int
    {
        if (!config('services.SPORTMONKS_TOKEN')) {
            $this->error('SPORTMONKS_TOKEN is missing.');

            return self::FAILURE;
        }

        $locale = (string) ($this->option('locale') ?: 'ar');
        $seasonIds = $this->resolveSeasonIds();

        if ($seasonIds->isEmpty()) {
            $this->warn('No seasons found to update.');

            return self::SUCCESS;
        }

        $this->info('Updating standings for ' . $seasonIds->count() . ' season(s).');

        $updated = 0;
        $failed = 0;

        foreach ($seasonIds as $seasonId) {
            $this->line("Season {$seasonId}: syncing...");

            $result = $standings->syncSeasonStandings($seasonId, $locale);

            if (!data_get($result, 'ok')) {
                $failed++;
                $message = data_get($result, 'message', 'Unknown error');
                $status = data_get($result, 'status');
                $suffix = $status ? " (status {$status})" : '';

                $this->error("Season {$seasonId}: {$message}{$suffix}");

                continue;
            }

            $this->forgetStandingCaches($standings, $seasonId, $locale);

            $count = (int) data_get($result, 'count', 0);
            $updated++;

            $this->info("Season {$seasonId}: updated {$count} row(s).");
        }

        $this->info("Standings update finished. Updated: {$updated}, Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function resolveSeasonIds(): Collection
    {
        $seasonId = (int) $this->option('season_id');

        if ($seasonId > 0) {
            return collect([$seasonId]);
        }

        $leagueId = (int) $this->option('league_id');

        if ($leagueId > 0) {
            $seasonIds = collect();
            $league = League::query()->find($leagueId);

            if ($league && $league->current_season_id) {
                $seasonIds->push((int) $league->current_season_id);
            }

            $currentSeasonId = Season::query()
                ->where('league_id', $leagueId)
                ->where('is_current', true)
                ->value('id');

            if ($currentSeasonId) {
                $seasonIds->push((int) $currentSeasonId);
            }

            return $this->cleanSeasonIds($seasonIds);
        }

        if ($this->option('all')) {
            return $this->cleanSeasonIds(Season::query()->pluck('id'));
        }

        $seasonIds = Season::query()
            ->where('is_current', true)
            ->pluck('id')
            ->merge(
                League::query()
                    ->whereNotNull('current_season_id')
                    ->pluck('current_season_id')
            );

        return $this->cleanSeasonIds($seasonIds);
    }

    private function cleanSeasonIds(Collection $seasonIds): Collection
    {
        return $seasonIds
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();
    }

    private function forgetStandingCaches(
        SportmonksStandingService $standings,
        int $seasonId,
        string $locale
    ): void {
        collect([$locale, 'ar', 'en'])
            ->unique()
            ->each(fn ($cacheLocale) => $standings->forgetStandingsCache($seasonId, $cacheLocale));
    }
}
