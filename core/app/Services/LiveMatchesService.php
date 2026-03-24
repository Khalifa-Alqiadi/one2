<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\League;
use App\Models\Round;
use App\Models\Fixture;
use App\Services\ApiClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class LiveMatchesService
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    // =========================
    // Helpers
    // =========================

    public function isFixtureTimeLive($startingAt, bool $isFinished): bool
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

    public function fetchFixtureLiveFromSportmonks(int $fixtureId, string $token, string $locale): ?array
    {
        $url = "https://api.sportmonks.com/v3/football/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}"
            . "&include=state;scores;periods;events";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $match = data_get($res, 'json.data', []);
        if (!$match) return null;

        // scores
        $scoresArr = data_get($match, 'scores', []) ?? [];
        [$homeScore, $awayScore] = $this->extractGoalsFromScores($scoresArr);

        // minute (اولاً time ثم periods ثم events)
        $minute =
            data_get($match, 'time.minute')
            ?? data_get($match, 'time.current_minute')
            ?? data_get($match, 'time.added_time')
            ?? null;

        if (!is_numeric($minute)) {
            $minute = $this->extractMinuteFromPeriods((array) data_get($match, 'periods', []));
        }

        if (!is_numeric($minute)) {
            $minute = $this->extractMinuteFromEvents((array) data_get($match, 'events', []));
        }

        $minute = is_numeric($minute) ? (int) $minute : null;

        // state
        $stateName = (string) data_get($match, 'state.name', '');
        $rawCode = data_get($match, 'state.short_code')
            ?? data_get($match, 'state.code')
            ?? data_get($match, 'state.developer_name')
            ?? null;

        $stateCode = $this->normalizeStateCode($rawCode, $stateName);

        $resultInfo = (string) data_get($match, 'result_info', '');
        $isFinished = $this->isFinishedState($stateCode, $stateName, $resultInfo);
        $isLive     = $this->isLiveState($stateCode, $stateName);

        $isTimeLive = $this->isFixtureTimeLive(data_get($match, 'starting_at'), $isFinished);

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
        elseif ($this->isHalfTimeState($stateCode, $stateName)) $status = 'HT';
        elseif ($isLive) $status = 'LIVE';

        return [
            'id' => (int) data_get($match, 'id'),
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'minute' => $minute,
            'state_code' => $stateCode,
            'state_name' => $stateName,
            'is_finished' => $isFinished,
            'status' => $status, // ✅ مهم
        ];
    }

    public function extractMinuteFromEvents(array $events): ?int
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

    // ✅ scores extractor قوي
    public function extractGoalsFromScores($scores): array
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

    public function extractMinuteFromPeriods(array $periods): ?int
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

    public function normalizeStateCode($rawCode, string $stateName): ?string
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

    public function isLiveState(?string $stateCode, string $stateName): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);

        // الحالات اللي تعتبر لعب فعلي
        if (in_array($code, ['LIVE', 'INPLAY', '1H', '2H', 'ET', 'PEN'], true)) {
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

    public function isHalfTimeState(?string $stateCode, string $stateName): bool
    {
        $code = strtoupper((string)$stateCode);
        $name = mb_strtolower($stateName);

        if (in_array($code, ['HT', 'HALFTIME', 'HALF_TIME', 'BREAK'], true)) {
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

    public function isFinishedState(?string $stateCode, string $stateName, string $resultInfo): bool
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

    public function normalizeEvents(array $events, int $homeId, int $awayId): array
    {
        return collect($events)
            ->filter(fn($e) => is_array($e))
            ->map(function ($e) use ($homeId, $awayId) {

                // minute label
                $minute = (int) (data_get($e, 'minute') ?? 0);
                $extra  = data_get($e, 'extra_minute');
                $minuteLabel = $minute > 0
                    ? ($minute . (is_numeric($extra) && (int)$extra > 0 ? '+' . (int)$extra : '') . "'")
                    : '';

                // side
                $pid  = (int) (data_get($e, 'participant_id') ?? 0);
                $side = ($pid === $homeId) ? 'home' : (($pid === $awayId) ? 'away' : 'neutral');

                // type
                $typeCode = strtolower((string)(
                    data_get($e, 'type.code')
                    ?? data_get($e, 'type.developer_name')
                    ?? data_get($e, 'type_code')
                    ?? ''
                ));

                $typeName = (string)(
                    data_get($e, 'type.name')
                    ?? data_get($e, 'name')
                    ?? ''
                );

                // players (الأسماء عندك جاهزة)
                $playerName        = (string)(data_get($e, 'player_name') ?? data_get($e, 'player.display_name') ?? data_get($e, 'player.name') ?? '');
                $relatedPlayerName = (string)(data_get($e, 'related_player_name') ?? data_get($e, 'related_player.display_name') ?? data_get($e, 'related_player.name') ?? '');

                $playerImg  = (string)(data_get($e, 'player.image_path') ?? '');
                $relatedImg = (string)(data_get($e, 'related_player.image_path') ?? '');

                // detect kinds
                $isGoal = in_array($typeCode, ['goal', 'penalty', 'own_goal'], true);
                $isSub  = str_contains($typeCode, 'sub'); // substitution/sub
                $isCard = in_array($typeCode, ['yellowcard', 'yellow_card', 'redcard', 'red_card', 'secondyellow', 'second_yellow'], true)
                    || str_contains($typeCode, 'card');

                // build structure for UI
                if ($isSub) {
                    // SportMonks: غالبًا player = OUT و related = IN (بس أحيانًا العكس)
                    $outName = $playerName;
                    $inName  = $relatedPlayerName;

                    $outImg = $playerImg;
                    $inImg  = $relatedImg;

                    // fallback swap لو واضح العكس (لو out فاضي و in موجود)
                    if ($outName === '' && $inName !== '') {
                        [$outName, $inName] = [$inName, $outName];
                        [$outImg,  $inImg] = [$inImg,  $outImg];
                    }

                    return [
                        'minute' => $minute,
                        'minute_label' => $minuteLabel,
                        'side' => $side,
                        'kind' => 'sub',
                        'label' => 'تبديل لاعب',
                        'sub' => [
                            'in' => [
                                'name' => $inName,
                                'image' => $inImg,
                                'number' => null, // إذا عندك endpoint lineups نقدر نملأه
                                'pos' => null,
                            ],
                            'out' => [
                                'name' => $outName,
                                'image' => $outImg,
                                'number' => null,
                                'pos' => null,
                            ],
                        ],
                        'raw' => $e,
                    ];
                }

                if ($isGoal) {
                    return [
                        'minute' => $minute,
                        'minute_label' => $minuteLabel,
                        'side' => $side,
                        'kind' => 'goal',
                        'label' => $typeName ?: 'هدف',
                        'goal' => [
                            'scorer_name'  => $playerName,
                            'scorer_image' => $playerImg,
                            'assist_name'  => $relatedPlayerName, // أحيانًا تكون assist وأحيانًا null
                            'scoreline'    => (string)(data_get($e, 'result') ?? ''), // "1-0"
                            'info'         => (string)(data_get($e, 'info') ?? ''),
                            'addition'     => (string)(data_get($e, 'addition') ?? ''), // "1st Goal"
                        ],
                        'raw' => $e,
                    ];
                }

                if ($isCard) {
                    return [
                        'minute' => $minute,
                        'minute_label' => $minuteLabel,
                        'side' => $side,
                        'kind' => 'card',
                        'label' => $typeName ?: 'بطاقة',
                        'card' => [
                            'player_name' => $playerName,
                            'player_image' => $playerImg,
                            'type_code' => $typeCode,
                        ],
                        'raw' => $e,
                    ];
                }

                return [
                    'minute' => $minute,
                    'minute_label' => $minuteLabel,
                    'side' => $side,
                    'kind' => 'other',
                    'label' => $typeName ?: 'حدث',
                    'raw' => $e,
                ];
            })
            // لو تبي كل الأحداث لا تحذف شيء
            // ->filter(fn($x) => in_array($x['kind'], ['sub','goal','card'], true))
            ->sortBy(fn($x) => (int)($x['minute'] ?? 0))
            ->values()
            ->all();
    }

}
