<?php

namespace App\Http\Controllers\Custom;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LeagueTabsController extends Controller
{
    private function curlGet(string $url): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $res = curl_exec($ch);

        if ($res === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['ok' => false, 'error' => $err];
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($res, true);

        if (!empty($json['message'])) {
            return ['ok' => false, 'http' => $code, 'error' => $json['message'], 'raw' => $json];
        }

        return ['ok' => true, 'http' => $code, 'json' => $json];
    }

    public function index(Request $request, int $leagueId)
    {
        $this->website_status();
        $token = '4XCcE7HqgnhHhHUosTNOEdjQJvM7EX63bJqH9U0aVUJ3sJ0w3qkmWR8MPiou';
        $locale = Helper::currentLanguage()->code; // إذا SportMonks يدعمها عندك

        // ✅ لو ما أرسل season_id: نحاول نجيبه تلقائيًا من league seasons (إن توفر)
        $seasonId = (int) $request->get('season_id', 0);

        // 1) League info (name/logo + seasons)
        $leagueUrl = "https://api.sportmonks.com/v3/football/leagues/{$leagueId}"
            . "?api_token={$token}&locale={$locale}&include=seasons";

        $leagueRes = $this->curlGet($leagueUrl);
        $league    = $leagueRes['ok'] ? data_get($leagueRes, 'json.data') : null;


        if (!$seasonId && $league) {
            $seasons = collect(data_get($league, 'seasons.data', data_get($league, 'seasons', [])));

            // حاول تختار الموسم الحالي/الأحدث
            $picked = $seasons->sortByDesc(function ($s) {
                return data_get($s, 'starting_at', data_get($s, 'id', 0));
            })->first();

            $seasonId = (int) data_get($picked, 'id', 0);
        }

        // 2) Fixtures (نجيب 100 ونفلتر محلياً حسب league_id + season_id)
        $fixturesUrl = "https://api.sportmonks.com/v3/football/fixtures"
            . "?api_token={$token}"
            . "&include=participants;league;state;scores"
            . "&locale={$locale}"
            . "&per_page=100";
        
        $fixturesRes = $this->curlGet($fixturesUrl);
        $fixturesAll = $fixturesRes['ok'] ? (data_get($fixturesRes, 'json.data', []) ?? []) : [];

        
        $fixtures = collect($fixturesAll)
            ->where('league_id', $leagueId)
            ->sortBy('starting_at')
            ->values()
            ->all();

        $round = null;
        $roundId = data_get($fixtures, '0.round_id');

        if ($roundId) {
            $roundUrl = "https://api.sportmonks.com/v3/football/rounds/{$roundId}"
                . "?api_token={$token}&locale={$locale}";

            $roundRes = $this->curlGet($roundUrl);

            if ($roundRes['ok']) {
                $round = data_get($roundRes, 'json.data');
            }
        }
        // 3) Standings (لو seasonId موجود)
        $standings = [];
        $standingsErr = null;

        if ($seasonId > 0) {
            $standingsUrl = "https://api.sportmonks.com/v3/football/standings/seasons/{$seasonId}"
                . "?api_token={$token}&locale={$locale}"
                . "&include=participant;details.type;form";

            $standingsRes = $this->curlGet($standingsUrl);
            if ($standingsRes['ok']) {
                $standings = data_get($standingsRes, 'json.data', []) ?? [];
            } else {
                $standingsErr = $standingsRes['error'] ?? null;
            }
        }

        // 4) Players (squad) — نجيب أول 6 فرق من standings (خفيف)
        $playersBlocks = [];
        $playersErr = null;
        // dd($standings);
        try {
            $teamIds = collect($standings)
                ->pluck('participant_id')
                ->values();
                // dd($teamIds);
            
            foreach ($teamIds as $teamId) {
                $teamUrl = "https://api.sportmonks.com/v3/football/teams/{$teamId}"
                    . "?api_token={$token}&locale={$locale}";

                $teamRes = $this->curlGet($teamUrl);
                
                if (!$teamRes['ok']) continue;

                $team = data_get($teamRes, 'json.data');
                if (!$team) continue;

                $playersBlocks[] = [
                    'team'  => $team['name'] ?? '',
                    'logo'  => $team['image_path'] ?? '',
                    'squad' => data_get($team, 'squad.data', data_get($team, 'squad', [])),
                ];
            }
        } catch (\Throwable $e) {
            $playersErr = $e->getMessage();
        }

        // 5) Stats (Placeholder — نقدر نربط Topscorers لاحقًا حسب المتاح في اشتراكك)
        $stats = [
            'note' => ($locale === 'ar')
                ? 'الإحصائيات: اربطها لاحقًا (مثل الهدافين/أفضل صانعي فرص) حسب الـ endpoints المتاحة في اشتراكك.'
                : 'Stats: connect later (e.g., top scorers) depending on endpoints available in your plan.',
        ];

        return view('frontEnd.custom.show', [
            'leagueId' => $leagueId,
            'seasonId' => $seasonId,
            'locale' => $locale,

            'league' => $league,
            'fixtures' => $fixtures,

            'standings' => $standings,
            'standingsErr' => $standingsErr,

            'playersBlocks' => $playersBlocks,
            'playersErr' => $playersErr,

            'stats' => $stats,
            'round' => $round,

            'leagueErr' => $leagueRes['ok'] ? null : ($leagueRes['error'] ?? null),
            'fixturesErr' => $fixturesRes['ok'] ? null : ($fixturesRes['error'] ?? null),
        ]);
    }

    public function show(Request $request, int $teamId)
    {
        $this->website_status();
        $token = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code; // إذا SportMonks يدعمها عندك

        // 1) Team info + venue + country
        $teamUrl = "https://api.sportmonks.com/v3/football/teams/{$teamId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=venue;country";

        $teamRes = $this->curlGet($teamUrl);
        if (!$teamRes['ok']) {
            abort(404, 'Team not found');
        }

        $team = data_get($teamRes, 'json.data');

        // 2) Squad (players)
        $squadUrl = "https://api.sportmonks.com/v3/football/teams/{$teamId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=squad";

        $squadRes = $this->curlGet($squadUrl);
        $squad = data_get($squadRes, 'json.data.squad.data', []);

        // 3) آخر المباريات (fixtures)
        $fixturesUrl = "https://api.sportmonks.com/v3/football/fixtures"
            . "?api_token={$token}"
            . "&include=participants;league;state;scores"
            . "&locale={$locale}"
            . "&per_page=100";

        $fixturesRes = $this->curlGet($fixturesUrl);

        $fixtures = collect(data_get($fixturesRes, 'json.data', []))
            ->filter(function ($fx) use ($teamId) {
                $ids = collect($fx['participants'] ?? [])->pluck('id');
                return $ids->contains($teamId);
            })
            ->sortByDesc('starting_at')
            ->take(10)
            ->values()
            ->all();

        $round = null;
        $roundId = data_get($fixtures, '0.round_id');

        if ($roundId) {
            $roundUrl = "https://api.sportmonks.com/v3/football/rounds/{$roundId}"
                . "?api_token={$token}&locale={$locale}";

            $roundRes = $this->curlGet($roundUrl);

            if ($roundRes['ok']) {
                $round = data_get($roundRes, 'json.data');
            }
        }

        return view('frontEnd.custom.club-details', [
            'team' => $team,
            'squad' => $squad,
            'fixtures' => $fixtures,
            'locale' => $locale,
            'round' => $round,
        ]);
    }

    public function website_status()
    {
        // Check the website Status
        if (!Auth::check()) {
            $site_status = Helper::GeneralSiteSettings("site_status");
            if ($site_status == 0) {
                echo view("frontEnd.closed", ["close_message" => Helper::GeneralSiteSettings("close_msg")])->render();
                exit();
            }
        }
    }
}
