<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Models\League;
use App\Models\Round;
use App\Models\Season;
use App\Models\Stage;
use App\Services\FetchFixtureDetailsFromSportmonksService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Throwable;

class AdvanceSeasonStructure extends Command
{
    protected $signature = 'football:advance-season-structure
        {--season_id= : SportMonks season ID}
        {--league_id= : Limit update to one league}
        {--locale=ar : SportMonks locale}
        {--force : Sync even when no completed current round/stage is detected}
        {--dry-run : Show what would run without saving}
        {--refresh-fixtures=1 : Refresh unfinished current fixtures before checking}
        {--lookback-hours=72 : Window used for recently finished fixtures}
        {--max-requests=450 : API request budget passed to football:update-current-season}
        {--request-sleep=500 : Milliseconds between SportMonks requests}
        {--batch-sleep=2 : Seconds between fixture database batches}
        {--fixture-batch=25 : Fixtures saved per database batch}';

    protected $description = 'Advance football rounds/stages after fixtures finish and refresh next knockout fixtures from SportMonks';

    public function handle(FetchFixtureDetailsFromSportmonksService $fixtureDetails): int
    {
        $targets = $this->resolveTargets();

        if ($targets->isEmpty()) {
            $this->warn('No season targets found.');

            return self::SUCCESS;
        }

        $locale = (string) ($this->option('locale') ?: 'ar');
        $force = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');
        $refreshFixtures = (bool) ((int) $this->option('refresh-fixtures'));
        $lookbackHours = max((int) $this->option('lookback-hours'), 1);
        $failed = 0;

        foreach ($targets as $target) {
            $seasonId = (int) $target['season_id'];
            $leagueId = $target['league_id'] ? (int) $target['league_id'] : null;

            $this->newLine();
            $this->line('Checking season ' . $seasonId . ($leagueId ? " / league {$leagueId}" : '') . '...');

            $completedBefore = $this->completedCurrentScopes($seasonId, $leagueId);
            $finishedFromRefresh = 0;

            if ($refreshFixtures && !$dryRun) {
                $finishedFromRefresh = $this->refreshCurrentOpenFixtures(
                    $seasonId,
                    $leagueId,
                    $locale,
                    $lookbackHours,
                    $fixtureDetails
                );
            }

            $completedAfter = $this->completedCurrentScopes($seasonId, $leagueId);
            $recentFinished = $this->hasRecentlyFinishedFixture($seasonId, $leagueId, $lookbackHours);
            $completedScopes = $completedAfter->isNotEmpty() ? $completedAfter : $completedBefore;
            $shouldSync = $force || $finishedFromRefresh > 0 || $completedScopes->isNotEmpty() || $recentFinished;

            if (!$shouldSync) {
                $this->line('No completed current round/stage or recently finished fixture detected.');
                continue;
            }

            if ($completedScopes->isNotEmpty()) {
                $this->line('Completed scope(s): ' . $completedScopes->implode(', '));
            }

            if ($dryRun) {
                $this->warn('Dry run: would call football:update-current-season and normalize current round/stage flags.');
                continue;
            }

            $exitCode = $this->syncSeasonStructure($seasonId, $leagueId, $locale);

            if ($exitCode !== self::SUCCESS) {
                $failed++;
                continue;
            }

            $progress = $this->normalizeLocalProgress($seasonId, $leagueId);

            $this->info(
                'Progress normalized. Current stage: '
                . ($progress['current_stage_id'] ?: '-')
                . ', current round: '
                . ($progress['current_round_id'] ?: '-')
            );
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function resolveTargets(): Collection
    {
        $seasonId = (int) $this->option('season_id');
        $leagueId = (int) $this->option('league_id');

        if ($seasonId > 0) {
            $season = Season::query()->find($seasonId);

            return collect([[
                'season_id' => $seasonId,
                'league_id' => $leagueId > 0 ? $leagueId : ($season ? (int) $season->league_id : null),
            ]]);
        }

        if ($leagueId > 0) {
            $league = League::query()->find($leagueId);
            $currentSeasonId = $league ? (int) $league->current_season_id : 0;

            if ($currentSeasonId <= 0) {
                $currentSeasonId = (int) Season::query()
                    ->where('league_id', $leagueId)
                    ->where('is_current', true)
                    ->value('id');
            }

            return $currentSeasonId > 0
                ? collect([['season_id' => $currentSeasonId, 'league_id' => $leagueId]])
                : collect();
        }

        $seasonTargets = Season::query()
            ->where('is_current', true)
            ->orderBy('league_id')
            ->get(['id', 'league_id'])
            ->map(fn (Season $season) => [
                'season_id' => (int) $season->id,
                'league_id' => (int) $season->league_id,
            ]);

        $leagueTargets = League::query()
            ->whereNotNull('current_season_id')
            ->orderBy('id')
            ->get(['id', 'current_season_id'])
            ->map(fn (League $league) => [
                'season_id' => (int) $league->current_season_id,
                'league_id' => (int) $league->id,
            ]);

        return $seasonTargets
            ->merge($leagueTargets)
            ->filter(fn (array $target) => (int) $target['season_id'] > 0)
            ->unique('season_id')
            ->values();
    }

    private function refreshCurrentOpenFixtures(
        int $seasonId,
        ?int $leagueId,
        string $locale,
        int $lookbackHours,
        FetchFixtureDetailsFromSportmonksService $fixtureDetails
    ): int {
        $token = (string) config('services.SPORTMONKS_TOKEN');

        if ($token === '') {
            $this->warn('SPORTMONKS_TOKEN is missing. Skipping fixture refresh.');

            return 0;
        }

        $currentRoundIds = $this->targetRounds($seasonId, $leagueId)
            ->where('is_current', true)
            ->pluck('id');

        $currentStageIds = $this->targetStages($seasonId, $leagueId)
            ->where('is_current', true)
            ->pluck('id');

        $fixtures = $this->targetFixtures($seasonId, $leagueId)
            ->where('is_finished', false)
            ->whereNotNull('starting_at')
            ->whereBetween('starting_at', [now()->subHours($lookbackHours), now()->addMinutes(30)])
            ->when($currentRoundIds->isNotEmpty() || $currentStageIds->isNotEmpty(), function ($query) use ($currentRoundIds, $currentStageIds) {
                $query->where(function ($scope) use ($currentRoundIds, $currentStageIds) {
                    if ($currentRoundIds->isNotEmpty()) {
                        $scope->orWhereIn('round_id', $currentRoundIds);
                    }

                    if ($currentStageIds->isNotEmpty()) {
                        $scope->orWhereIn('stage_id', $currentStageIds);
                    }
                });
            })
            ->orderBy('starting_at')
            ->limit(80)
            ->get();

        if ($fixtures->isEmpty()) {
            return 0;
        }

        $finished = 0;

        foreach ($fixtures as $fixture) {
            try {
                $data = $fixtureDetails->fetchFixtureDetailsFromSportmonks((int) $fixture->id, $token, $locale);

                if (!$data) {
                    continue;
                }

                $fixtureDetails->persistFixtureDetails($fixture, $data, false);
                Cache::forget("sportmonks:fixture_live:{$fixture->id}:{$locale}");
                Cache::forget("sportmonks:fixture_details:{$fixture->id}:{$locale}");

                if ($this->fixtureDataIsFinished($data)) {
                    $finished++;
                }
            } catch (Throwable $exception) {
                $this->warn("Fixture {$fixture->id} refresh failed: {$exception->getMessage()}");
            }
        }

        if ($finished > 0) {
            $this->line("Finished fixtures detected from refresh: {$finished}");
        }

        return $finished;
    }

    private function syncSeasonStructure(int $seasonId, ?int $leagueId, string $locale): int
    {
        $options = [
            '--season_id' => $seasonId,
            '--locale' => $locale,
            '--max-requests' => max((int) $this->option('max-requests'), 0),
            '--request-sleep' => max((int) $this->option('request-sleep'), 0),
            '--batch-sleep' => max((int) $this->option('batch-sleep'), 0),
            '--fixture-batch' => max((int) $this->option('fixture-batch'), 1),
        ];

        if ($leagueId) {
            $options['--league_id'] = $leagueId;
        }

        $this->line('Syncing stages, rounds and fixtures from SportMonks...');
        $exitCode = Artisan::call('football:update-current-season', $options);
        $output = trim(Artisan::output());

        if ($output !== '') {
            $this->line($output);
        }

        if ($exitCode !== self::SUCCESS) {
            $this->error("football:update-current-season failed with exit code {$exitCode}.");
        }

        return $exitCode;
    }

    private function normalizeLocalProgress(int $seasonId, ?int $leagueId): array
    {
        $roundStats = $this->roundStats($seasonId, $leagueId);
        $currentRoundId = null;

        foreach ($roundStats as $stat) {
            Round::query()->whereKey($stat['id'])->update(['finished' => $stat['finished']]);
        }

        $currentRound = $roundStats->first(fn (array $stat) => $stat['is_current'] && !$stat['finished']);
        $nextRound = $roundStats->first(fn (array $stat) => !$stat['finished'] && $stat['fixture_count'] > 0);

        if ($currentRound || $nextRound) {
            $currentRoundId = (int) ($currentRound['id'] ?? $nextRound['id']);
        }

        if ($roundStats->isNotEmpty()) {
            Round::query()->whereIn('id', $roundStats->pluck('id'))->update(['is_current' => false]);

            if ($currentRoundId) {
                Round::query()->whereKey($currentRoundId)->update(['is_current' => true]);
            }
        }

        $stageStats = $this->stageStats($seasonId, $leagueId);
        $currentStageId = null;

        foreach ($stageStats as $stat) {
            Stage::query()->whereKey($stat['id'])->update(['finished' => $stat['finished']]);
        }

        $currentStage = $stageStats->first(fn (array $stat) => $stat['is_current'] && !$stat['finished']);
        $roundStage = $currentRoundId
            ? $stageStats->first(fn (array $stat) => (int) $stat['id'] === (int) data_get($roundStats->firstWhere('id', $currentRoundId), 'stage_id'))
            : null;
        $nextStage = $stageStats->first(fn (array $stat) => !$stat['finished'] && ($stat['fixture_count'] > 0 || $stat['round_count'] > 0));

        if ($currentStage || $roundStage || $nextStage) {
            $currentStageId = (int) ($currentStage['id'] ?? $roundStage['id'] ?? $nextStage['id']);
        }

        if ($stageStats->isNotEmpty()) {
            Stage::query()->whereIn('id', $stageStats->pluck('id'))->update(['is_current' => false]);

            if ($currentStageId) {
                Stage::query()->whereKey($currentStageId)->update(['is_current' => true]);
            }
        }

        return [
            'current_round_id' => $currentRoundId,
            'current_stage_id' => $currentStageId,
        ];
    }

    private function completedCurrentScopes(int $seasonId, ?int $leagueId): Collection
    {
        $completed = collect();

        foreach ($this->targetRounds($seasonId, $leagueId)->where('is_current', true)->get() as $round) {
            if ($this->allFixturesFinished('round_id', (int) $round->id, $seasonId, $leagueId)) {
                $completed->push("round:{$round->id}");
            }
        }

        foreach ($this->targetStages($seasonId, $leagueId)->where('is_current', true)->get() as $stage) {
            if ($this->allFixturesFinished('stage_id', (int) $stage->id, $seasonId, $leagueId)) {
                $completed->push("stage:{$stage->id}");
            }
        }

        return $completed;
    }

    private function hasRecentlyFinishedFixture(int $seasonId, ?int $leagueId, int $lookbackHours): bool
    {
        $currentRoundIds = $this->targetRounds($seasonId, $leagueId)
            ->where('is_current', true)
            ->pluck('id');

        $currentStageIds = $this->targetStages($seasonId, $leagueId)
            ->where('is_current', true)
            ->pluck('id');

        return $this->targetFixtures($seasonId, $leagueId)
            ->where('is_finished', true)
            ->where('updated_at', '>=', now()->subHours($lookbackHours))
            ->when($currentRoundIds->isNotEmpty() || $currentStageIds->isNotEmpty(), function ($query) use ($currentRoundIds, $currentStageIds) {
                $query->where(function ($scope) use ($currentRoundIds, $currentStageIds) {
                    if ($currentRoundIds->isNotEmpty()) {
                        $scope->orWhereIn('round_id', $currentRoundIds);
                    }

                    if ($currentStageIds->isNotEmpty()) {
                        $scope->orWhereIn('stage_id', $currentStageIds);
                    }
                });
            })
            ->exists();
    }

    private function roundStats(int $seasonId, ?int $leagueId): Collection
    {
        return $this->targetRounds($seasonId, $leagueId)
            ->orderByRaw('starting_at is null')
            ->orderBy('starting_at')
            ->orderBy('id')
            ->get()
            ->map(function (Round $round) use ($seasonId, $leagueId) {
                $fixtures = $this->targetFixtures($seasonId, $leagueId)->where('round_id', $round->id);
                $fixtureCount = (clone $fixtures)->count();
                $unfinishedCount = (clone $fixtures)->where('is_finished', false)->count();

                return [
                    'id' => (int) $round->id,
                    'stage_id' => $round->stage_id ? (int) $round->stage_id : null,
                    'is_current' => (bool) $round->is_current,
                    'fixture_count' => $fixtureCount,
                    'finished' => $fixtureCount > 0 && $unfinishedCount === 0,
                ];
            });
    }

    private function stageStats(int $seasonId, ?int $leagueId): Collection
    {
        return $this->targetStages($seasonId, $leagueId)
            ->orderByRaw('sort_order is null')
            ->orderBy('sort_order')
            ->orderByRaw('starting_at is null')
            ->orderBy('starting_at')
            ->orderBy('id')
            ->get()
            ->map(function (Stage $stage) use ($seasonId, $leagueId) {
                $fixtures = $this->targetFixtures($seasonId, $leagueId)->where('stage_id', $stage->id);
                $fixtureCount = (clone $fixtures)->count();
                $unfinishedFixtureCount = (clone $fixtures)->where('is_finished', false)->count();

                $rounds = $this->targetRounds($seasonId, $leagueId)->where('stage_id', $stage->id);
                $roundCount = (clone $rounds)->count();
                $unfinishedRoundCount = (clone $rounds)->where('finished', false)->count();

                return [
                    'id' => (int) $stage->id,
                    'is_current' => (bool) $stage->is_current,
                    'fixture_count' => $fixtureCount,
                    'round_count' => $roundCount,
                    'finished' => $fixtureCount > 0
                        ? $unfinishedFixtureCount === 0
                        : ($roundCount > 0 && $unfinishedRoundCount === 0),
                ];
            });
    }

    private function allFixturesFinished(string $column, int $id, int $seasonId, ?int $leagueId): bool
    {
        $fixtures = $this->targetFixtures($seasonId, $leagueId)->where($column, $id);
        $count = (clone $fixtures)->count();

        return $count > 0 && (clone $fixtures)->where('is_finished', false)->count() === 0;
    }

    private function fixtureDataIsFinished(array $data): bool
    {
        $stateCode = strtoupper((string) data_get($data, 'state_code', ''));
        $status = strtoupper((string) data_get($data, 'status', ''));

        return (bool) data_get($data, 'is_finished', false)
            || $status === 'FT'
            || in_array($stateCode, ['FT', 'CANC', 'ABD', 'SUSP'], true);
    }

    private function targetFixtures(int $seasonId, ?int $leagueId)
    {
        return Fixture::query()
            ->where('season_id', $seasonId)
            ->when($leagueId, fn ($query) => $query->where('league_id', $leagueId));
    }

    private function targetRounds(int $seasonId, ?int $leagueId)
    {
        return Round::query()
            ->where('season_id', $seasonId)
            ->when($leagueId, fn ($query) => $query->where('league_id', $leagueId));
    }

    private function targetStages(int $seasonId, ?int $leagueId)
    {
        return Stage::query()
            ->where('season_id', $seasonId)
            ->when($leagueId, fn ($query) => $query->where('league_id', $leagueId));
    }
}
