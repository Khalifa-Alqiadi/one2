<?php

namespace App\Http\Controllers\Football;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fixture;
use App\Services\LiveMatchesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LivescoresController extends Controller
{
    private LiveMatchesService $handleMatchesService;

    public function __construct(LiveMatchesService $handleMatchesService)
    {
        $this->handleMatchesService = $handleMatchesService;
    }

    public function index()
    {
        $this->website_status();
        $liveMatches = Fixture::where('is_finished', false)
            ->where(function ($query) {
                $query->where('starting_at', '<=', now()->addHours(3))
                    ->orWhereNull('starting_at');
            })
            ->get();
        $PageTitle = __('frontend.live_matches');
        $PageDescription = __('frontend.live_matches');
        return view('frontEnd.football.livescores', compact('liveMatches', 'PageTitle', 'PageDescription'));
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

    // =========================
    // B) Proxy live endpoint
    // (يجلب المباشر من SportMonks)
    // =========================
    public function liveProxy(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids')))
            ->filter()
            ->map(fn($v) => (int) $v)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['ok' => true, 'fixtures' => []]);
        }

        $rows = Fixture::query()
            ->whereIn('id', $ids)
            ->select(['id', 'starting_at', 'is_finished', 'home_score', 'away_score', 'minute', 'state_name', 'state_code'])
            ->get()
            ->keyBy('id');

        $token  = config('services.SPORTMONKS_TOKEN');
        $locale = Helper::currentLanguage()->code ?? 'ar';

        $out = [];

        foreach ($ids as $id) {
            $fx = $rows->get($id);
            if (!$fx) continue;

            $isFinishedDb = (bool) $fx->is_finished;
            // ✅ الوقت يستخدم فقط لتقرير هل نسوي polling أم لا
            $shouldPoll = $this->handleMatchesService->isFixtureTimeLive($fx->starting_at, false);
            // إذا المباراة منتهية في DB أو خارج نافذة المتابعة => رجّع DB فقط
            if ($isFinishedDb || !$shouldPoll) {
                $out[] = [
                    'id' => (int) $fx->id,
                    'home_score' => is_numeric($fx->home_score) ? (int)$fx->home_score : null,
                    'away_score' => is_numeric($fx->away_score) ? (int)$fx->away_score : null,
                    'minute' => is_numeric($fx->minute) ? (int)$fx->minute : null,
                    'state_code' => $fx->state_code,
                    'state_name' => $fx->state_name,
                    'is_finished' => $isFinishedDb,
                    'status' => $isFinishedDb ? 'FT' : 'NS',
                ];
                continue;
            }

            // ✅ Poll from API (مع كاش بسيط اختياري)
            $cacheKey = "sportmonks:fixture_live:{$id}:{$locale}";

            // $liveData = Cache::remember($cacheKey, 8, function () use ($id, $token, $locale) {
            $liveData = $this->handleMatchesService->fetchFixtureLiveFromSportmonks($id, $token, $locale);
            // });

            // ✅ إذا فشل API: لا تجبرها Live
            if (!$liveData) {
                $out[] = [
                    'id' => (int) $fx->id,
                    'home_score' => is_numeric($fx->home_score) ? (int)$fx->home_score : null,
                    'away_score' => is_numeric($fx->away_score) ? (int)$fx->away_score : null,
                    'minute' => is_numeric($fx->minute) ? (int)$fx->minute : null,
                    'state_code' => $fx->state_code,
                    'state_name' => $fx->state_name,
                    'is_finished' => $isFinishedDb,
                    'status' => $isFinishedDb ? 'FT' : 'UNK', // ✅ مجهول لكن لا تغيّر حالة المباراة
                ];
                continue;
            }

            // dd($liveData['status']);

            // ✅ حدّث DB فقط إذا API أكد FT
            if (($liveData['status'] ?? '') === 'FT' && $liveData['state_code'] === 'FT') {
                \App\Models\Fixture::where('id', $id)->update([
                    'home_score'    => $liveData['home_score'],
                    'away_score'    => $liveData['away_score'],
                    'ft_home_score' => $liveData['home_score'],
                    'ft_away_score' => $liveData['away_score'],
                    'is_finished'   => 1,
                    'state_code'    => $liveData['state_code'] ?? 'FT',
                    'state_name'    => $liveData['state_name'] ?? 'Finished',
                    'minute'        => null,
                ]);

                Cache::forget($cacheKey);
            }

            $out[] = $liveData;
        }


        return response()->json(['ok' => true, 'fixtures' => $out]);
    }
}
