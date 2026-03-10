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

            // الأفضل تستخدم Http بدل curl custom (أكثر استقرار + retries)
            $response = Http::timeout(60)
                ->retry(3, 1000)
                ->get($url, [
                    'api_token' => $this->token,
                    'locale'    => $this->locale,
                    'page'      => $page,
                ]);

            if (!$response->successful()) {
                // خلّيه يفشل عشان Laravel يعيد المحاولة حسب tries
                throw new \RuntimeException(
                    "SportMonks request failed. Status: {$response->status()} Body: {$response->body()}"
                );
            }

            $json  = $response->json();
            $teams = $json['data'] ?? [];

            foreach ($teams as $team) {
                // تخطي الفرق بدون دولة
                if (empty($team['country_id'])) {
                    continue;
                }
                if($team['id'] > 0){
                    Team::updateOrCreate(
                        ['id' => $team['id']], // استخدام id من SportMonks كـ primary key
                        [
                            'name_ar'    => $team['name'] ?? null,
                            'image_path' => $team['image_path'] ?? null,
                            'country_id' => $team['country_id'],
                            'venue_id'   => $team['venue_id'] ?? null,
                            'short_code' => $team['short_code'] ?? null,
                            'sport_id'   => $team['id'] ?? null,
                        ]
                    );
                }
                // Team::updateOrCreate(
                //     ['sport_id' => $team['id']],
                //     [
                //         'name_ar'    => $team['name'] ?? null,
                //         'image_path' => $team['image_path'] ?? null,
                //         'country_id' => $team['country_id'],
                //         'venue_id'   => $team['venue_id'] ?? null,
                //         'short_code' => $team['short_code'] ?? null,
                //     ]
                // );

                $saved++;
            }

            $hasMore = (bool) data_get($json, 'pagination.has_more', false);
            $page++;

        } while ($hasMore);

        // إذا تحب تسجل النتيجة في log
        // \Log::info("SportMonks Teams Sync Done", ['saved' => $saved]);
    }

    public function failed(Throwable $e): void
    {
        // هنا تقدر تسجل فشل الـ job أو ترسل إشعار
        // \Log::error("SportMonks Teams Sync FAILED", ['error' => $e->getMessage()]);
    }
}
