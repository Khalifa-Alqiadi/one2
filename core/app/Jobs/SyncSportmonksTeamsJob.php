<?php

namespace App\Jobs;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class SyncSportmonksTeamsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200; // 20 minutes
    public int $tries = 3;

    public function __construct(
        public string $token,
        public string $locale = 'en',
    ) {}

    public function handle(): void
    {
        $page = 1;
        $saved = 0;

        do {
            $url = "https://api.sportmonks.com/v3/football/teams";

            $primaryResponse = Http::timeout(60)
                ->retry(3, 1000)
                ->get($url, [
                    'api_token' => $this->token,
                    'locale'    => 'ar',
                    'include'   => "coaches.coach;coaches.coach.trophies;statistics;trophies.trophy",
                    'page'      => $page,
                ]);

            $englishResponse = Http::timeout(60)
                ->retry(3, 1000)
                ->get($url, [
                    'api_token' => $this->token,
                    'locale'    => 'en',
                    'page'      => $page,
                ]);

            if (!$primaryResponse->successful()) {
                throw new \RuntimeException(
                    "SportMonks Arabic teams request failed. Status: {$primaryResponse->status()} Body: {$primaryResponse->body()}"
                );
            }

            if (!$englishResponse->successful()) {
                throw new \RuntimeException(
                    "SportMonks English teams request failed. Status: {$englishResponse->status()} Body: {$englishResponse->body()}"
                );
            }

            $primaryJson  = $primaryResponse->json();
            $englishJson  = $englishResponse->json();

            $primaryTeams = collect($primaryJson['data'] ?? []);

            $englishTeams = collect($englishJson['data'] ?? []);

            $englishMap = $englishTeams->keyBy('id');

            foreach ($primaryTeams as $team) {
                dd($team);
                if (empty($team['country_id'])) {
                    continue;
                }

                $teamId = (int) ($team['id'] ?? 0);

                if ($teamId <= 0) {
                    continue;
                }

                $englishTeam = $englishMap->get($teamId, []);

                $existTeam = Team::find($teamId);

                if ($existTeam) {
                    $existTeam->update([
                        'name_en'    => $englishTeam['name'] ?? null,
                        'image_path' => $team['image_path'] ?? null,
                        'country_id' => $team['country_id'] ?? null,
                        'venue_id'   => $team['venue_id'] ?? null,
                        'short_code' => $team['short_code'] ?? null,
                        'sport_id'   => $team['sport_id'] ?? null,
                    ]);
                } else {
                    Team::create([
                        'id'         => $teamId,
                        'name_ar'    => $team['name'] ?? null,
                        'name_en'    => $englishTeam['name'] ?? null,
                        'image_path' => $team['image_path'] ?? null,
                        'country_id' => $team['country_id'] ?? null,
                        'venue_id'   => $team['venue_id'] ?? null,
                        'short_code' => $team['short_code'] ?? null,
                        'sport_id'   => $team['sport_id'] ?? null,
                    ]);
                }

                $saved++;
            }

            $hasMore = (bool) data_get($primaryJson, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);

        // \Log::info("SportMonks Teams Sync Done", ['saved' => $saved]);
    }

    public function failed(Throwable $e): void
    {
        // هنا تقدر تسجل فشل الـ job أو ترسل إشعار
        // \Log::error("SportMonks Teams Sync FAILED", ['error' => $e->getMessage()]);
    }
}
