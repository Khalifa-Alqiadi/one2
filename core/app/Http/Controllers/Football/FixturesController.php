<?php

namespace App\Http\Controllers\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FixturesController extends Controller
{
    public function index(Request $request)
    {
        $this->website_status();

        $token  = config('services.SPORTMONKS_TOKEN');
        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';

        $start = Carbon::today()->subDays(2);
        $end = Carbon::today()->addDays(5);

        $dates = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[] = [
                'key' => $date->toDateString(),
                'label' => $date->isToday()
                    ? 'اليوم'
                    : ($date->isYesterday()
                        ? 'أمس'
                        : ($date->isTomorrow()
                            ? 'غدًا'
                            : $date->translatedFormat('l'))),
                'date' => $date->translatedFormat('M d'),
                'is_today' => $date->isToday(),
            ];
        }

        // $date    = now()->toDateString();
        $date = $request->get('date', now()->toDateString());
        $tab = now();
        if($date !== null){
            $tab = $date;
        }
        $fixtures = Fixture::whereDate('starting_at', $date)
            ->with(['homeTeam', 'awayTeam', 'league', 'season'])
            ->whereHas('season', function ($q) {
                $q->where('is_current', true);
            })
            ->orderBy('starting_at')
            ->paginate(40);

        return view('frontEnd.custom.matches', [
            'locale' => $locale,
            'matches' => $fixtures,
            'activeTab' => $tab,
            'dates' => $dates,
        ]);
    }

    /**
     * Returns JSON for today's fixtures used by client-side polling.
     */
    public function todayJson(Request $request)
    {
        $token  = config('services.SPORTMONKS_TOKEN');
        $localeRaw = Helper::currentLanguage()->code ?? 'ar';
        $locale = in_array($localeRaw, ['ar', 'en']) ? $localeRaw : 'en';

        $leagueId = (int) $request->query('league_id', 0); // optional filter

        $includes = "participants;league;state;scores;periods;events";
        $today = now()->toDateString();

        $err = null;
        $raw = $this->fetchAllFixturesByDate($today, $token, $locale, $includes, $err, 200, 30);

        $items = collect($raw)
            ->map(fn($fx) => Helper::enrichFixture((array)$fx))
            ->when($leagueId > 0, fn($q) => $q->filter(fn($fx) => (int) data_get($fx, 'league_id', 0) === $leagueId))
            ->map(function ($fx) {
                // Return slim payload for polling
                $parts = collect(data_get($fx, 'participants.data', data_get($fx, 'participants', [])));
                $home = $parts->firstWhere('meta.location', 'home') ?: $parts->get(0);
                $away = $parts->firstWhere('meta.location', 'away') ?: $parts->get(1);

                [$homeScore, $awayScore] = $this->extractScoreForUi($fx, $home, $away);

                return [
                    'id' => data_get($fx, 'id'),
                    'home_score' => $homeScore,
                    'away_score' => $awayScore,
                    'computed_minute' => data_get($fx, 'computed_minute'),
                    'computed_is_live' => (bool) data_get($fx, 'computed_is_live'),
                    'state' => [
                        'code' => strtolower((string) data_get($fx, 'state.code', '')),
                        'name' => (string) data_get($fx, 'state.name', ''),
                    ],
                    // display labels (optional)
                    'display_top' => null,
                    'display_sub' => null,
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'ok' => true,
            'err' => $err,
            'fixtures' => $items,
        ]);
    }


    /**
     * ✅ جلب مباريات يوم محدد بصفحة واحدة فقط (20) + يرجع pagination meta
     */
    private function fetchFixturesByDatePaged(
        string $date,
        string $token,
        string $locale,
        string $includes,
        ?string &$err = null,
        int $perPage = 20,
        int $page = 1
    ): array {
        $url = "https://api.sportmonks.com/v3/football/fixtures/date/{$date}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=" . urlencode($includes)
            . "&per_page={$perPage}"
            . "&page={$page}";

        $res = $this->curlGet($url);

        if (!$res['ok']) {
            $err = $res['error'] ?? 'Fixtures request failed';
            return [
                'items' => [],
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => 1,
                    'has_more' => false,
                    'total' => 0,
                    'per_page' => $perPage,
                ],
            ];
        }

        $items = data_get($res, 'json.data', []) ?? [];

        // ✅ pagination ممكن تكون في أكثر من مكان حسب الرد
        $pagination = data_get($res, 'json.pagination', []);
        if (empty($pagination)) {
            $pagination = data_get($res, 'json.meta.pagination', []);
        }

        $currentPage = (int) (data_get($pagination, 'current_page')
            ?? data_get($pagination, 'currentPage')
            ?? $page);

        $lastPage = (int) (data_get($pagination, 'last_page')
            ?? data_get($pagination, 'lastPage')
            ?? 1);

        $total = (int) (data_get($pagination, 'total')
            ?? data_get($pagination, 'total_items')
            ?? data_get($pagination, 'totalItems')
            ?? 0);

        $per = (int) (data_get($pagination, 'per_page')
            ?? data_get($pagination, 'perPage')
            ?? $perPage);

        // ✅ لو last_page مش موجود لكن total موجود نحسبه
        if ($lastPage <= 1 && $total > 0 && $per > 0) {
            $lastPage = (int) ceil($total / $per);
        }

        // ✅ لو API يعطي has_more فقط
        $hasMore = (bool) (data_get($pagination, 'has_more')
            ?? data_get($pagination, 'hasMore')
            ?? ($currentPage < $lastPage));

        // ✅ fallback: لو عدد العناصر == perPage غالباً فيه صفحات إضافية
        if ($lastPage <= 1 && count($items) >= $perPage) {
            $hasMore = true;
            $lastPage = $currentPage + 1; // تقدير مؤقت يسمح بإظهار pagination
        }

        return [
            'items' => $items,
            'pagination' => [
                'current_page' => $currentPage,
                'last_page'    => $lastPage,
                'has_more'     => $hasMore,
                'total'        => $total,
                'per_page'     => $per,
            ],
        ];
    }


    private function fetchAllFixturesByDate(string $date, string $token, string $locale, string $includes, ?string &$err = null, int $perPage = 200, int $maxPages = 30): array
    {
        $all = collect();
        $page = 1;
        $hasMore = true;

        while ($hasMore && $page <= $maxPages) {
            $res = $this->fetchFixturesByDatePaged($date, $token, $locale, $includes, $err, $perPage, $page);

            $all = $all->merge($res['items'] ?? []);
            $hasMore = (bool) data_get($res, 'pagination.has_more', false);

            $page++;
            if (empty($res['items'])) break;
        }

        return $all->values()->all();
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
