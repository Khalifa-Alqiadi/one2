<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class SportmonksService
{
    /**
     * جلب مباريات ضمن مدى زمني مع الـ includes الكاملة.
     * النتيجة مخزّنة في الكاش حسب (from, to) لتقليل استهلاك حصة الطلبات.
     */
    public function fixturesBetween(string $from, string $to): array
    {
        $key = "sportmonks:fixtures:{$from}:{$to}";
        $ttl = config('sportmonks.cache_ttl');

        return Cache::remember($key, $ttl, function () use ($from, $to) {
            return $this->fetchAllPages($from, $to);
        });
    }

    /**
     * جلب مباراة واحدة بالـ ID (للتفاصيل الموسّعة).
     */
    public function fixture(int $id): ?array
    {
        $key = "sportmonks:fixture:{$id}";
        $ttl = config('sportmonks.cache_ttl');
        $locale = 'ar'; // لضمان تنسيق التاريخ بالعربية

        return Cache::remember($key, $ttl, function () use ($id, $locale) {
            $url = rtrim(config('sportmonks.base_url'), '/') . "/fixtures/{$id}";

            $response = Http::timeout(config('sportmonks.timeout'))
                ->acceptJson()
                ->get($url, [
                    'api_token' => config('sportmonks.token'),
                    'include'   => config('sportmonks.includes'),
                    'locale'    => $locale,
                ]);

            if ($response->failed()) {
                Log::warning('Sportmonks single-fixture fetch failed', [
                    'id'     => $id,
                    'status' => $response->status(),
                    'body'   => mb_substr($response->body(), 0, 400),
                ]);
                return null;
            }
            return $response->json('data');
        });
    }

    /**
     * يسحب كل الصفحات لمدى زمني محدّد.
     */
    protected function fetchAllPages(string $from, string $to): array
    {
        $all      = [];
        $page     = 1;
        $maxPages = config('sportmonks.max_pages');
        $url      = rtrim(config('sportmonks.base_url'), '/') . "/fixtures/between/{$from}/{$to}";
        $locale  = 'ar'; // لضمان تنسيق التاريخ بالعربية

        while ($page <= $maxPages) {
            $response = Http::timeout(config('sportmonks.timeout'))
                ->acceptJson()
                ->get($url, [
                    'api_token' => config('sportmonks.token'),
                    'locale'    => $locale,
                    'include'   => config('sportmonks.includes'),
                    'per_page'  => config('sportmonks.per_page'),
                    'page'      => $page,
                ]);

            if ($response->failed()) {
                Log::warning('Sportmonks fetch failed', [
                    'page'   => $page,
                    'status' => $response->status(),
                    'body'   => mb_substr($response->body(), 0, 400),
                ]);
                break;
            }

            $payload  = $response->json();
            $fixtures = $payload['data'] ?? [];

            if (empty($fixtures)) break;

            $all = array_merge($all, $fixtures);

            $hasMore = $payload['pagination']['has_more'] ?? false;
            if (!$hasMore) break;

            $page++;
            usleep(200_000); // احترام للـ rate limit
        }

        // ترتيب من الأحدث للأقدم
        usort($all, function ($a, $b) {
            return strcmp($b['starting_at'] ?? '', $a['starting_at'] ?? '');
        });

        return $all;
    }

    /**
     * تطبيع المباراة إلى شكل جاهز للعرض (تسهيلاً على الـ Views).
     */
    public static function normalize(array $f): array
    {
        $home = $away = null;
        foreach ($f['participants'] ?? [] as $p) {
            $loc = $p['meta']['location'] ?? null;
            if ($loc === 'home') $home = $p;
            if ($loc === 'away') $away = $p;
        }

        [$hs, $as] = [null, null];
        foreach ($f['scores'] ?? [] as $s) {
            if (($s['description'] ?? '') !== 'CURRENT') continue;
            $loc = $s['score']['participant'] ?? null;
            if ($loc === 'home') $hs = $s['score']['goals'] ?? null;
            if ($loc === 'away') $as = $s['score']['goals'] ?? null;
        }

        $dt = !empty($f['starting_at'])
            ? Carbon::parse($f['starting_at'])
            : null;

        return [
            'id'          => $f['id'] ?? null,
            'date'        => $dt?->format('Y-m-d'),
            'time'        => $dt?->format('H:i'),
            'date_full'   => $dt?->isoFormat('dddd، D MMMM YYYY'),
            'league'      => $f['league']['name'] ?? null,
            'league_id'   => $f['league_id'] ?? null,
            'state'       => $f['state']['name'] ?? ($f['state']['short_name'] ?? '—'),
            'state_short' => $f['state']['short_name'] ?? null,
            'venue'       => $f['venue']['name'] ?? null,
            'home'        => [
                'name'  => $home['name']       ?? '—',
                'image' => $home['image_path'] ?? null,
                'score' => $hs,
            ],
            'away'        => [
                'name'  => $away['name']       ?? '—',
                'image' => $away['image_path'] ?? null,
                'score' => $as,
            ],
            'raw'         => $f, // نحتفظ بالخام للتفاصيل العميقة
        ];
    }
}
