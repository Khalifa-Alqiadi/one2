<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Models\League;
use App\Models\Round;
use App\Models\Season;
use App\Models\Stage;
use App\Services\LiveMatchesService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class UpdateCurrentSeasonStructure extends Command
{
    protected $signature = 'football:update-current-season
        {--season_id= : Sync one SportMonks season ID}
        {--league_id= : Limit sync to one league}
        {--locale=ar : SportMonks locale}
        {--per-page=50 : SportMonks page size}
        {--fixture-batch=25 : Fixtures saved per database batch}
        {--request-sleep=500 : Milliseconds to wait after each API request}
        {--batch-sleep=2 : Seconds to wait after each fixture database batch}
        {--max-requests=450 : Stop before passing this API request count, use 0 for no limit}
        {--retries=2 : Retries for failed or rate-limited API requests}
        {--rate-limit-sleep=60 : Seconds to wait after HTTP 429 when Retry-After is missing}';

    protected $description = 'Update current season stages, rounds and fixtures from SportMonks in batches';

    private string $token = '';
    private string $locale = 'ar';
    private int $perPage = 50;
    private int $fixtureBatch = 25;
    private int $requestSleepMs = 500;
    private int $batchSleepSeconds = 2;
    private int $maxRequests = 450;
    private int $retries = 2;
    private int $rateLimitSleepSeconds = 60;
    private int $apiRequests = 0;

    public function handle(LiveMatchesService $liveMatches): int
    {
        $this->token = (string) config('services.SPORTMONKS_TOKEN');

        if ($this->token === '') {
            $this->error('SPORTMONKS_TOKEN is missing.');

            return self::FAILURE;
        }

        $this->readOptions();

        $targets = $this->resolveTargets();

        if ($targets->isEmpty()) {
            $this->warn('No current seasons found to sync.');

            return self::SUCCESS;
        }

        $this->info('Syncing ' . $targets->count() . ' season target(s).');
        $this->line("API request budget: " . ($this->maxRequests > 0 ? $this->maxRequests : 'unlimited'));

        $totals = [
            'stages' => 0,
            'rounds' => 0,
            'fixtures' => 0,
            'failed' => 0,
        ];

        foreach ($targets as $target) {
            $seasonId = (int) $target['season_id'];
            $leagueId = $target['league_id'] ? (int) $target['league_id'] : null;

            $this->newLine();
            $this->line('Season ' . $seasonId . ($leagueId ? " / League {$leagueId}" : '') . ': starting...');

            try {
                $result = $this->syncSeason($seasonId, $leagueId, $liveMatches);
            } catch (Throwable $exception) {
                $totals['failed']++;
                $this->error("Season {$seasonId}: " . $exception->getMessage());

                if ($this->hasReachedApiBudget()) {
                    break;
                }

                continue;
            }

            $totals['stages'] += $result['stages'];
            $totals['rounds'] += $result['rounds'];
            $totals['fixtures'] += $result['fixtures'];

            $this->info(
                "Season {$seasonId}: stages {$result['stages']}, rounds {$result['rounds']}, fixtures {$result['fixtures']}"
            );

            if ($this->hasReachedApiBudget()) {
                $this->warn('API request budget reached. Stopping.');
                break;
            }
        }

        $this->newLine();
        $this->info(
            "Finished. Stages: {$totals['stages']}, Rounds: {$totals['rounds']}, Fixtures: {$totals['fixtures']}, Failed seasons: {$totals['failed']}, API requests: {$this->apiRequests}"
        );

        return $totals['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function readOptions(): void
    {
        $this->locale = (string) ($this->option('locale') ?: 'ar');
        $this->perPage = min(max((int) $this->option('per-page'), 1), 50);
        $this->fixtureBatch = min(max((int) $this->option('fixture-batch'), 1), 100);
        $this->requestSleepMs = max((int) $this->option('request-sleep'), 0);
        $this->batchSleepSeconds = max((int) $this->option('batch-sleep'), 0);
        $this->maxRequests = max((int) $this->option('max-requests'), 0);
        $this->retries = max((int) $this->option('retries'), 0);
        $this->rateLimitSleepSeconds = max((int) $this->option('rate-limit-sleep'), 1);
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

    private function syncSeason(int $seasonId, ?int $leagueId, LiveMatchesService $liveMatches): array
    {
        return [
            'stages' => $this->syncStages($seasonId, $leagueId),
            'rounds' => $this->syncRounds($seasonId, $leagueId),
            'fixtures' => $this->syncFixtures($seasonId, $leagueId, $liveMatches),
        ];
    }

    private function syncStages(int $seasonId, ?int $leagueId): int
    {
        $page = 1;
        $saved = 0;

        do {
            $json = $this->sportmonksGet("stages/seasons/{$seasonId}", [
                'locale' => $this->locale,
                'include' => 'type',
                'per_page' => $this->perPage,
                'page' => $page,
            ]);

            $stages = collect(data_get($json, 'data', []))
                ->filter(fn ($stage) => is_array($stage) && $this->belongsToLeague($stage, $leagueId))
                ->values();

            foreach ($stages as $stage) {
                if ($this->saveStage($stage)) {
                    $saved++;
                }
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        return $saved;
    }

    private function syncRounds(int $seasonId, ?int $leagueId): int
    {
        $page = 1;
        $saved = 0;

        do {
            $json = $this->sportmonksGet("rounds/seasons/{$seasonId}", [
                'locale' => $this->locale,
                'per_page' => $this->perPage,
                'page' => $page,
            ]);

            $rounds = collect(data_get($json, 'data', []))
                ->filter(fn ($round) => is_array($round) && $this->belongsToLeague($round, $leagueId))
                ->unique('id')
                ->values();

            foreach ($rounds as $round) {
                if ($this->saveRound($round)) {
                    $saved++;
                }
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        return $saved;
    }

    private function syncFixtures(int $seasonId, ?int $leagueId, LiveMatchesService $liveMatches): int
    {
        $page = 1;
        $saved = 0;

        do {
            $json = $this->sportmonksGet('fixtures', [
                'locale' => $this->locale,
                'filters' => "fixtureSeasons:{$seasonId}",
                'include' => 'participants;state;scores;periods;venue',
                'per_page' => $this->perPage,
                'page' => $page,
            ]);

            $fixtures = collect(data_get($json, 'data', []))
                ->filter(fn ($fixture) => is_array($fixture) && $this->belongsToLeague($fixture, $leagueId))
                ->values();

            foreach ($fixtures->chunk($this->fixtureBatch) as $batch) {
                DB::transaction(function () use ($batch, $liveMatches, &$saved) {
                    foreach ($batch as $fixture) {
                        if ($this->saveFixture($fixture, $liveMatches)) {
                            $saved++;
                        }
                    }
                });

                if ($this->batchSleepSeconds > 0) {
                    sleep($this->batchSleepSeconds);
                }
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        return $saved;
    }

    private function saveStage(array $stage): bool
    {
        $stageId = (int) data_get($stage, 'id', 0);

        if ($stageId <= 0) {
            return false;
        }

        $payload = [
            'league_id' => (int) data_get($stage, 'league_id'),
            'season_id' => (int) data_get($stage, 'season_id'),
            'type_id' => data_get($stage, 'type_id'),
            'type_name' => data_get($stage, 'type.name')
                ?? data_get($stage, 'type.developer_name')
                ?? data_get($stage, 'type'),
            'sort_order' => data_get($stage, 'sort_order'),
            'finished' => (bool) data_get($stage, 'finished', false),
            'is_current' => (bool) data_get($stage, 'is_current', false),
            'starting_at' => data_get($stage, 'starting_at'),
            'ending_at' => data_get($stage, 'ending_at'),
            'payload' => $stage,
        ];

        $payload[$this->locale === 'en' ? 'name_en' : 'name_ar'] = data_get($stage, 'name');

        Stage::query()->updateOrCreate(['id' => $stageId], $payload);

        return true;
    }

    private function saveRound(array $round): bool
    {
        $roundId = (int) data_get($round, 'id', 0);

        if ($roundId <= 0) {
            return false;
        }

        Round::query()->updateOrCreate(
            ['id' => $roundId],
            [
                'league_id' => (int) data_get($round, 'league_id'),
                'season_id' => (int) data_get($round, 'season_id'),
                'stage_id' => data_get($round, 'stage_id') ? (int) data_get($round, 'stage_id') : null,
                'name' => data_get($round, 'name'),
                'finished' => (bool) data_get($round, 'finished', false),
                'is_current' => (bool) data_get($round, 'is_current', false),
                'games_in_current_week' => (bool) data_get($round, 'games_in_current_week', false),
                'starting_at' => data_get($round, 'starting_at'),
                'ending_at' => data_get($round, 'ending_at'),
            ]
        );

        return true;
    }

    private function saveFixture(array $match, LiveMatchesService $liveMatches): bool
    {
        $fixtureId = (int) data_get($match, 'id', 0);

        if ($fixtureId <= 0) {
            return false;
        }

        $participants = collect(data_get($match, 'participants', []));
        $home = $participants->first(fn ($participant) => data_get($participant, 'meta.location') === 'home');
        $away = $participants->first(fn ($participant) => data_get($participant, 'meta.location') === 'away');

        $homeId = $this->participantId($home);
        $awayId = $this->participantId($away);

        $stateName = (string) (data_get($match, 'state.name') ?: data_get($match, 'state_name') ?: '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name')
            ?? data_get($match, 'state.state');

        $stateCode = $liveMatches->normalizeStateCode($rawCode, $stateName);
        $isFinished = $liveMatches->isFinishedState(
            $stateCode,
            $stateName,
            (string) data_get($match, 'result_info', '')
        );
        $isLive = $liveMatches->isLiveState($stateCode, $stateName);

        [$homeScore, $awayScore] = $liveMatches->extractGoalsFromScores(data_get($match, 'scores', []) ?? []);

        $minute = data_get($match, 'time.minute')
            ?? data_get($match, 'time.current_minute')
            ?? data_get($match, 'time.added_time');

        if (!is_numeric($minute)) {
            $minute = $liveMatches->extractMinuteFromPeriods((array) data_get($match, 'periods', []));
        }

        $minute = $isLive && is_numeric($minute) ? (int) $minute : null;

        Fixture::query()->updateOrCreate(
            ['id' => $fixtureId],
            [
                'league_id' => (int) data_get($match, 'league_id'),
                'season_id' => (int) data_get($match, 'season_id'),
                'round_id' => data_get($match, 'round_id') ? (int) data_get($match, 'round_id') : null,
                'stage_id' => data_get($match, 'stage_id') ? (int) data_get($match, 'stage_id') : null,
                'venue_id' => data_get($match, 'venue_id')
                    ? (int) data_get($match, 'venue_id')
                    : (data_get($match, 'venue.id') ? (int) data_get($match, 'venue.id') : null),
                'home_team_id' => $homeId,
                'away_team_id' => $awayId,
                'starting_at' => data_get($match, 'starting_at'),
                'state_id' => data_get($match, 'state_id') ?: data_get($match, 'state.id'),
                'state_name' => $stateName ?: null,
                'state_code' => $stateCode ?: null,
                'home_score' => is_numeric($homeScore) ? (int) $homeScore : null,
                'away_score' => is_numeric($awayScore) ? (int) $awayScore : null,
                'is_finished' => $isFinished ? 1 : 0,
                'ft_home_score' => $isFinished && is_numeric($homeScore) ? (int) $homeScore : null,
                'ft_away_score' => $isFinished && is_numeric($awayScore) ? (int) $awayScore : null,
                'minute' => $minute,
                'payload' => $match,
            ]
        );

        Cache::forget("sportmonks:fixture_live:{$fixtureId}:{$this->locale}");
        Cache::forget("sportmonks:fixture_details:{$fixtureId}:{$this->locale}");

        return true;
    }

    private function sportmonksGet(string $endpoint, array $params, int $attempt = 0): array
    {
        if ($this->hasReachedApiBudget()) {
            throw new RuntimeException("API request budget reached ({$this->maxRequests}).");
        }

        $baseUrl = rtrim((string) config('sportmonks.base_url', 'https://api.sportmonks.com/v3/football'), '/');
        $url = $baseUrl . '/' . ltrim($endpoint, '/');
        $params['api_token'] = $this->token;

        $this->apiRequests++;

        try {
            $response = Http::timeout(60)
                ->acceptJson()
                ->get($url, $params);
        } catch (Throwable $exception) {
            return $this->retryOrFail($endpoint, $params, $attempt, $exception->getMessage());
        } finally {
            if ($this->requestSleepMs > 0) {
                usleep($this->requestSleepMs * 1000);
            }
        }

        if ($response->status() === 429) {
            $seconds = (int) ($response->header('Retry-After') ?: $this->rateLimitSleepSeconds);

            return $this->retryOrFail($endpoint, $params, $attempt, "Rate limit hit. Retry after {$seconds} seconds.", $seconds);
        }

        if (!$response->successful()) {
            $body = mb_substr($response->body(), 0, 250);

            return $this->retryOrFail(
                $endpoint,
                $params,
                $attempt,
                "HTTP {$response->status()} from SportMonks: {$body}"
            );
        }

        $json = $response->json();

        if (!is_array($json)) {
            throw new RuntimeException('SportMonks returned a non-JSON response.');
        }

        if (data_get($json, 'errors')) {
            throw new RuntimeException('SportMonks returned API errors: ' . json_encode(data_get($json, 'errors')));
        }

        return $json;
    }

    private function retryOrFail(
        string $endpoint,
        array $params,
        int $attempt,
        string $message,
        ?int $sleepSeconds = null
    ): array {
        if ($attempt >= $this->retries || $this->hasReachedApiBudget()) {
            throw new RuntimeException($message);
        }

        $wait = $sleepSeconds ?? min(10, $attempt + 1);
        $this->warn("SportMonks request failed for {$endpoint}. Retry " . ($attempt + 1) . "/{$this->retries} after {$wait}s.");
        sleep($wait);

        return $this->sportmonksGet($endpoint, $params, $attempt + 1);
    }

    private function belongsToLeague(array $row, ?int $leagueId): bool
    {
        return !$leagueId || (int) data_get($row, 'league_id') === $leagueId;
    }

    private function participantId($participant): ?int
    {
        $id = data_get($participant, 'id') ?: data_get($participant, 'participant_id');

        return is_numeric($id) ? (int) $id : null;
    }

    private function hasReachedApiBudget(): bool
    {
        return $this->maxRequests > 0 && $this->apiRequests >= $this->maxRequests;
    }
}
