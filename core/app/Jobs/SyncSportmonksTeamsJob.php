<?php

namespace App\Jobs;

use App\Models\Team;
use App\Models\Trophy;
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
        public int $country_id = 0,
    ) {}

    public function handleOld(): void
    {
        $page = 1;
        $saved = 0;

        dd($this->country_id);

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

    public function handle(): void
    {
        $page = 1;
        $saved = 0;

        // dd($this->country_id);

        do {
            $url = "https://api.sportmonks.com/v3/football/teams";
            if($this->country_id > 0){
                $url = "https://api.sportmonks.com/v3/football/teams/countries/{$this->country_id}";
            }

            $primaryResponse = Http::timeout(60)
                ->retry(3, 1000)
                ->get($url, [
                    'api_token' => $this->token,
                    'locale'    => 'ar',
                    'include'   => 'coaches.coach;coaches.coach.trophies;statistics;trophies.trophy',
                    'page'      => $page,
                ]);

            $englishResponse = Http::timeout(60)
                ->retry(3, 1000)
                ->get($url, [
                    'api_token' => $this->token,
                    'locale'    => 'en',
                    'include'   => 'coaches.coach;coaches.coach.trophies;statistics;trophies.trophy',
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

            $primaryJson = $primaryResponse->json();
            $englishJson = $englishResponse->json();

            $primaryTeams = collect($primaryJson['data'] ?? []);
            $englishTeams = collect($englishJson['data'] ?? []);

            $englishMap = $englishTeams->keyBy('id');


            foreach ($primaryTeams as $team) {
                if (empty($team['country_id'])) {
                    continue;
                }

                $teamId = (int) data_get($team, 'id', 0);

                if ($teamId <= 0) {
                    continue;
                }

                $englishTeam = $englishMap->get($teamId, []);

                $teamModel = Team::find($teamId);
                if((bool) ($team['placeholder'] == false)){
                    if ($teamModel) {
                        $teamModel->update([
                            'name_en'    => data_get($englishTeam, 'name'),
                            'image_path' => data_get($team, 'image_path'),
                            'country_id' => data_get($team, 'country_id'),
                            'venue_id'   => data_get($team, 'venue_id'),
                            'short_code' => data_get($team, 'short_code'),
                            'sport_id'   => data_get($team, 'sport_id'),
                        ]);
                    } else {
                        $teamModel = Team::create([
                            'id'         => $teamId,
                            'name_ar'    => data_get($team, 'name'),
                            'name_en'    => data_get($englishTeam, 'name'),
                            'image_path' => data_get($team, 'image_path'),
                            'country_id' => data_get($team, 'country_id'),
                            'venue_id'   => data_get($team, 'venue_id'),
                            'short_code' => data_get($team, 'short_code'),
                            'sport_id'   => data_get($team, 'sport_id'),
                        ]);
                    }
                    // $this->syncTeamTrophies($teamModel, $team);
                }else{
                    if($teamModel){
                        $teamModel->delete();
                    }
                }

                // جوائز الفريق


                // المدربين + جوائزهم
                // $this->syncTeamCoachesAndTrophies($team);

                $saved++;
            }

            $hasMore = (bool) data_get($primaryJson, 'pagination.has_more', false);
            $page++;
        } while ($hasMore);
    }

    private function syncTeamTrophies(Team $teamModel, array $team): void
    {
        $teamTrophies = collect(data_get($team, 'trophies', []));

        foreach ($teamTrophies as $item) {
            $trophy = data_get($item, 'trophy', $item);

            $sportmonksTrophyId = (int) data_get($trophy, 'id', 0);

            if ($sportmonksTrophyId <= 0) {
                continue;
            }

            Trophy::updateOrCreate(
                [
                    'id'                   => data_get($trophy, 'id'),
                    'awardable_type'       => $teamModel->getMorphClass(),
                    'awardable_id'         => $teamModel->id,
                    'sportmonks_trophy_id' => $sportmonksTrophyId,
                ],
                [
                    'sportmonks_relation_id' => data_get($item, 'id'),
                    'name_ar'                => $this->resolveApiText(data_get($trophy, 'name')),
                    'name_en'                => $this->resolveApiText(data_get($trophy, 'name'), 'en'),
                    'country_id'             => data_get($trophy, 'country_id'),
                    'sport_id'               => data_get($trophy, 'sport_id'),
                    'season'                 => data_get($item, 'season'),
                    'date'                   => data_get($item, 'date'),
                    'description'            => $this->resolveApiText(data_get($trophy, 'description')),
                ]
            );
        }
    }

//     private function syncTeamCoachesAndTrophies(array $team): void
//     {
//         $coaches = collect(data_get($team, 'coaches', []));
//
//         foreach ($coaches as $coachItem) {
//             $coach = data_get($coachItem, 'coach', []);
//
//             $coachId = (int) data_get($coach, 'id', 0);
//
//             if ($coachId <= 0) {
//                 continue;
//             }
//
//             $coachModel = Coach::find($coachId);
//
//             if (!$coachModel) {
//                 $coachModel = Coach::create([
//                     'id'         => $coachId,
//                     'name_ar'    => $this->resolveApiText(data_get($coach, 'name')),
//                     'image_path' => data_get($coach, 'image_path'),
//                     'country_id' => data_get($coach, 'country_id'),
//                     'sport_id'   => data_get($coach, 'sport_id'),
//                 ]);
//             } else {
//                 $coachModel->update([
//                     'image_path' => data_get($coach, 'image_path'),
//                     'country_id' => data_get($coach, 'country_id'),
//                     'sport_id'   => data_get($coach, 'sport_id'),
//                 ]);
//             }
//
//             $coachTrophies = collect(data_get($coach, 'trophies', []));
//
//             foreach ($coachTrophies as $coachTrophy) {
//                 $sportmonksTrophyId = (int) (
//                     data_get($coachTrophy, 'trophy_id')
//                     ?? data_get($coachTrophy, 'id')
//                     ?? 0
//                 );
//
//                 if ($sportmonksTrophyId <= 0) {
//                     continue;
//                 }
//
//                 Trophy::updateOrCreate(
//                     [
//                         'awardable_type'       => $coachModel->getMorphClass(),
//                         'awardable_id'         => $coachModel->id,
//                         'sportmonks_trophy_id' => $sportmonksTrophyId,
//                     ],
//                     [
//                         'sportmonks_relation_id' => data_get($coachTrophy, 'id'),
//                         'name_ar'                => $this->resolveApiText(
//                             data_get($coachTrophy, 'name')
//                         ),
//                         'name_en'                => $this->resolveApiText(
//                             data_get($coachTrophy, 'name'),
//                             'en'
//                         ),
//                         'country_id'             => data_get($coachTrophy, 'country_id'),
//                         'sport_id'               => data_get($coachTrophy, 'sport_id'),
//                         'season'                 => data_get($coachTrophy, 'season'),
//                         'date'                   => data_get($coachTrophy, 'date'),
//                         'description'            => $this->resolveApiText(
//                             data_get($coachTrophy, 'description')
//                         ),
//                         'payload_json'           => $coachTrophy,
//                     ]
//                 );
//             }
//         }
//     }

    private function resolveApiText($value, string $locale = 'ar'): ?string
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
                ?? collect($value)->first(fn($item) => is_string($item) || is_numeric($item));
        }

        return null;
    }

    public function failed(Throwable $e): void
    {
        // هنا تقدر تسجل فشل الـ job أو ترسل إشعار
        // \Log::error("SportMonks Teams Sync FAILED", ['error' => $e->getMessage()]);
    }
}
