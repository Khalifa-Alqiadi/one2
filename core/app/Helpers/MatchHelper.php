<?php
namespace App\Helpers;

use App;
use App\Models\Menu;
use App\Services\ApiClientService;
use URL;

class MatchHelper
{

    static ApiClientService $apiClient;

    static function extractGoalsFromScores($scores): array
    {
        $scores = is_array($scores) ? $scores : [];
        $col = collect($scores)->sort();

        // CURRENT
        $current = $col->first(function ($s) {
            $typeCode = strtoupper((string) data_get($s, 'type.code', data_get($s, 'type', '')));
            $desc     = strtoupper((string) data_get($s, 'description', ''));
            return $typeCode === 'CURRENT' || str_contains($desc, 'CURRENT');
        });

        // FT
        $ft = $col->first(function ($s) {
            $typeCode = strtoupper((string) data_get($s, 'type.code', data_get($s, 'type', '')));
            $desc     = strtoupper((string) data_get($s, 'description', ''));
            return $typeCode === 'FT' || $typeCode === 'FULLTIME' || str_contains($desc, 'FT');
        });

        $row = $current ?: $ft;

        $home = data_get($row, 'score.home')
            ?? data_get($row, 'score.goals.home')
            ?? data_get($row, 'score.goals_home')
            ?? null;

        $away = data_get($row, 'score.away')
            ?? data_get($row, 'score.goals.away')
            ?? data_get($row, 'score.goals_away')
            ?? null;

        // fallback participant
        if ($home === null || $away === null) {
            $homeRow = $col->first(fn($s) => data_get($s, 'score.participant') === 'home');
            $awayRow = $col->first(fn($s) => data_get($s, 'score.participant') === 'away');

            $home = $home ?? data_get($homeRow, 'score.goals');
            $away = $away ?? data_get($awayRow, 'score.goals');
        }

        $home = is_numeric($home) ? (int)$home : null;
        $away = is_numeric($away) ? (int)$away : null;

        return [$home, $away];
    }

    static function fetchFixtureLiveFromSportmonks(int $fixtureId, string $token, string $locale): ?array
    {
        $url = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=state;scores;periods;events";

        $res = app(ApiClientService::class)->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $match = data_get($res, 'json.data', []);
        if (!$match) return null;

        // scores
        $scoresArr = data_get($match, 'scores', []) ?? [];
        [$homeScore, $awayScore] = MatchHelper::extractGoalsFromScores($scoresArr);

        // minute (اولاً time ثم periods ثم events)
        $minute =
            data_get($match, 'time.minute')
            ?? data_get($match, 'time.current_minute')
            ?? data_get($match, 'time.added_time')
            ?? null;

        if (!is_numeric($minute)) {
            $minute = MatchHelper::extractMinuteFromPeriods((array) data_get($match, 'periods', []));
        }

        if (!is_numeric($minute)) {
            $minute = MatchHelper::extractMinuteFromEvents((array) data_get($match, 'events', []));
        }

        $minute = is_numeric($minute) ? (int) $minute : null;

        // state
        $stateName = (string) data_get($match, 'state.name', '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name')
            ?? null;

        $stateCode = MatchHelper::normalizeStateCode($rawCode, $stateName);

        $resultInfo = (string) data_get($match, 'result_info', '');
        $isFinished = MatchHelper::isFinishedState($stateCode, $stateName, $resultInfo);
        $isLive     = MatchHelper::isLiveState($stateCode, $stateName);

        $isTimeLive = MatchHelper::isFixtureTimeLive(data_get($match, 'starting_at'), $isFinished);

        $isLive = $isLive || $isTimeLive;
        // return [
        //     'id' => (int) data_get($match, 'id'),
        //     'home_score' => is_numeric($homeScore) ? (int) $homeScore : null,
        //     'away_score' => is_numeric($awayScore) ? (int) $awayScore : null,
        //     'minute' => $minute,
        //     'state_code' => $stateCode,
        //     'state_name' => $stateName,
        //     'is_live' => $isLive && !$isFinished,
        //     'is_finished' => $isFinished,
        // ];
        $status = 'NS';
        if ($isFinished) $status = 'FT';
        elseif (MatchHelper::isHalfTimeState($stateCode, $stateName)) $status = 'HT';
        elseif ($isLive) $status = 'LIVE';

        return [
            'id' => (int) data_get($match,'id'),
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'minute' => $minute,
            'state_code' => $stateCode,
            'state_name' => $stateName,
            'is_finished' => $isFinished,
            'status' => $status, // ✅ مهم
        ];
    }

     static function extractMinuteFromEvents(array $events): ?int
    {
        $events = is_array($events) ? $events : [];
        if (!$events) return null;

        // خذ آخر حدث له minute
        $last = collect($events)
            ->filter(fn($e) => is_numeric(data_get($e, 'minute')) || is_numeric(data_get($e, 'time.minute')))
            ->sortBy(function ($e) {
                return (int) (data_get($e, 'minute', data_get($e, 'time.minute', 0)));
            })
            ->last();

        $m = data_get($last, 'minute') ?? data_get($last, 'time.minute');
        return is_numeric($m) ? (int) $m : null;
    }

    static function isFixtureTimeLive($startingAt, bool $isFinished): bool
    {
        if ($isFinished) return false;
        if (!$startingAt) return false;

        try {
            $start = \Carbon\Carbon::parse($startingAt);
        } catch (\Throwable $e) {
            return false;
        }

        $now = now();
        $from = $start->copy()->subMinutes(15);
        $to   = $start->copy()->addHours(3);

        return $now->between($from, $to);
    }

    static function extractMinuteFromPeriods(array $periods): ?int
    {
        $periods = is_array($periods) ? $periods : [];
        if (!$periods) return null;

        $col = collect($periods);

        // جرّب تجيب الـ current / inplay period
        $current = $col->first(function ($p) {
            $desc = mb_strtolower((string) data_get($p, 'description', ''));
            $type = mb_strtolower((string) data_get($p, 'type', data_get($p, 'type.code', '')));
            $isCurrent = (bool) data_get($p, 'is_current', false);

            return $isCurrent
                || str_contains($desc, 'current')
                || str_contains($desc, 'in play')
                || str_contains($type, 'current')
                || str_contains($type, 'inplay');
        });

        // لو ما وجد current خذ آخر فترة
        $p = $current ?: $col->sortBy(function ($x) {
            return (int) (data_get($x, 'sort_order', data_get($x, 'id', 0)));
        })->last();

        // بعضهم يرجع minute مباشرة
        $m =
            data_get($p, 'minute')
            ?? data_get($p, 'time.minute')
            ?? data_get($p, 'minutes')
            ?? null;

        // أو يحسبها من start/end (ثواني)
        // أحيانًا يكون start/end شكل: "00:12:34" أو "12:34"
        if (!is_numeric($m)) {
            $end = data_get($p, 'ended', data_get($p, 'end', data_get($p, 'ended_at')));
            if (is_numeric($end)) {
                $m = floor(((int)$end) / 60);
            }
        }

        return is_numeric($m) ? (int) $m : null;
    }

    static function normalizeStateCode($rawCode, string $stateName): ?string
    {
        $code = strtoupper(trim((string) $rawCode));
        $name = mb_strtolower(trim($stateName));

        if ($code === '' || $code === 'NULL') {
            if (
                str_contains($name, 'نهاية') || str_contains($name, 'انته') || str_contains($name, 'نهائ')
                || str_contains($name, 'finished') || str_contains($name, 'full time')
            ) return 'FT';

            if (str_contains($name, 'مباشر') || str_contains($name, 'live') || str_contains($name, 'in play') || str_contains($name, 'inplay')) return 'LIVE';

            if (str_contains($name, 'الشوط') || str_contains($name, 'half')) return 'HT';

            if (str_contains($name, 'لم تبدأ') || str_contains($name, 'not started') || str_contains($name, 'ns')) return 'NS';

            return null;
        }

        $code = str_replace([' ', '-'], '_', $code);

        $map = [
            'FINISHED' => 'FT',
            'FULLTIME' => 'FT',
            'FULL_TIME' => 'FT',
            'INPLAY' => 'LIVE',
            'IN_PLAY' => 'LIVE',
            'PLAYING' => 'LIVE',
            'HALFTIME' => 'HT',
            'HALF_TIME' => 'HT',
            'NOTSTARTED' => 'NS',
            'NOT_STARTED' => 'NS',
            'AET' => 'ET',
            'EXTRATIME' => 'ET',
            'EXTRA_TIME' => 'ET',
            'PENALTIES' => 'PEN',
            'POSTPONED' => 'POSTP',
            'CANCELLED' => 'CANC',
            'CANCELED' => 'CANC',
            'SUSPENDED' => 'SUSP',
            'ABANDONED' => 'ABD',
        ];

        $code = $map[$code] ?? $code;

        if (strlen($code) > 10) $code = substr($code, 0, 10);

        return $code ?: null;
    }

    static function isLiveState(?string $stateCode, string $stateName): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);

        // الحالات اللي تعتبر لعب فعلي
        if (in_array($code, ['LIVE','INPLAY','1H','2H','ET','PEN'], true)) {
            return true;
        }

        // لو النص يدل على لعب مباشر
        if (
            str_contains($name, 'مباشر') ||
            str_contains($name, 'live') ||
            str_contains($name, 'in play') ||
            str_contains($name, 'inplay')
        ) {
            return true;
        }

        return false;
    }

    static function isHalfTimeState(?string $stateCode, string $stateName): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);

        if (in_array($code, ['HT','HALFTIME','HALF_TIME','BREAK'], true)) {
            return true;
        }

        if (
            str_contains($name, 'half') ||
            str_contains($name, 'منتصف') ||
            str_contains($name, 'استراحة')
        ) {
            return true;
        }

        return false;
    }

    private function isFinishedState(?string $stateCode, string $stateName, string $resultInfo): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);
        $resultInfo = trim($resultInfo);

        if (in_array($code, ['FT', 'CANC', 'ABD', 'SUSP'], true)) return true;

        $isFinishedByText =
            str_contains($name, 'finished') ||
            str_contains($name, 'full') ||
            str_contains($name, 'ended') ||
            str_contains($name, 'final') ||
            str_contains($name, 'انته') ||
            str_contains($name, 'نهائ') ||
            str_contains($name, 'نهاية') ||
            str_contains($name, 'مكتمل');

        $isFinishedByResult = $resultInfo !== '';

        return $isFinishedByText || $isFinishedByResult;
    }
}
