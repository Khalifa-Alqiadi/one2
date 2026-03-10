{{-- Errors --}}
@if (!empty($fixturesErr))
    <div class="alert alert-danger">{{ $fixturesErr }}</div>
@endif
@if (!empty($roundsErr))
    <div class="alert alert-warning">{{ $roundsErr }}</div>
@endif

<div class="fixtures-board">

    <div class="fixtures-head d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            @if (!empty($prevRoundId))
                <a class="nav-arrow" href="{{ request()->fullUrlWithQuery(['round_id' => $prevRoundId]) }}">‹</a>
            @else
                <span class="nav-arrow disabled">‹</span>
            @endif

            @if (!empty($nextRoundId))
                <a class="nav-arrow" href="{{ request()->fullUrlWithQuery(['round_id' => $nextRoundId]) }}">›</a>
            @else
                <span class="nav-arrow disabled">›</span>
            @endif
        </div>

        <div class="fixtures-title">
            {{ $locale == 'ar' ? 'المباريات' : 'Fixtures' }}
            <span class="muted">
                : {{ $locale == 'ar' ? 'يوم المباراة' : 'Matchday' }}
                {{ $matchdayNumber ?? 0 }} {{ $locale == 'ar' ? 'من إجمالي' : 'of' }} {{ $totalRounds ?? 0 }}
            </span>

            @if (!empty($round) && data_get($round, 'name'))
                <div class="muted" style="font-size:12px;margin-top:4px;">
                    {{ $locale == 'ar' ? 'الجولة' : 'Round' }}: {{ data_get($round, 'name') }}
                </div>
            @endif
        </div>
    </div>

    @php
        // ✅ polling only if there is any likely live fixture in the current round
        $hasPossibleLive = collect($fixtures ?? [])->contains(function ($m) {
            $code = strtoupper((string) data_get($m, 'state_code', ''));
            return in_array($code, ['LIVE', 'INPLAY', 'HT'], true);
        });
    @endphp

    <div class="fixtures-grid">
        @forelse($fixtures as $m)
            @php
                $home = data_get($m, 'homeTeam');
                $away = data_get($m, 'awayTeam');

                $homeId = data_get($home, 'id');
                $awayId = data_get($away, 'id');

                $homeName = data_get($home, 'name', $locale == 'ar' ? 'المضيف' : 'Home');
                $awayName = data_get($away, 'name', $locale == 'ar' ? 'الضيف' : 'Away');

                $homeLogo = data_get($home, 'image_path', '');
                $awayLogo = data_get($away, 'image_path', '');

                $startAt = data_get($m, 'starting_at');
                $dt = $startAt ? \Carbon\Carbon::parse($startAt) : null;

                $stateCode = strtoupper((string) data_get($m, 'state_code', ''));
                $stateName = (string) data_get($m, 'state_name', '');

                // current score from DB
                $homeScore = data_get($m, 'home_score');
                $awayScore = data_get($m, 'away_score');
                $hasScore = is_numeric($homeScore) && is_numeric($awayScore);

                // finished from DB OR from state_code fallback
                $isFinished = (bool) data_get($m, 'is_finished', false);
                if (!$isFinished) {
                    $isFinished = in_array($stateCode, ['FT', 'AET', 'FT_PEN', 'FINISHED'], true);
                }

                // live by state_code
                $isLive = in_array($stateCode, ['LIVE', 'INPLAY', 'HT'], true);

                // postponed/canceled (optional)
                $isPostponed = in_array($stateCode, ['POSTPONED', 'PST'], true) || str_contains($stateName, 'مؤجل');
                $isCanceled = in_array($stateCode, ['CANCELLED', 'CAN'], true) || str_contains($stateName, 'ملغ');

                // minute from DB
                $minute = data_get($m, 'minute');

                // Left labels (top/sub)
                $leftTop = '';
                $leftSub = '';
                $leftTopHtml = null;

                if ($isCanceled || $isPostponed) {
                    $leftTop =
                        $stateName ?:
                        ($isPostponed
                            ? ($locale == 'ar'
                                ? 'مؤجلة'
                                : 'Postponed')
                            : ($locale == 'ar'
                                ? 'ملغاة'
                                : 'Canceled'));
                    $leftSub = $dt ? $dt->translatedFormat('d/m H:i') : '';
                } elseif ($isLive) {
                    $fmtMinute = null;
                    if (is_numeric($minute)) {
                        $mm = (int) $minute;
                        $fmtMinute = $mm > 90 ? '90+' . ($mm - 90) : (string) $mm;
                    }
                    $leftTopHtml = '';
                    if ($fmtMinute) {
                        $leftTopHtml .= '<span class="live-minute">' . e($fmtMinute) . '’' . '</span>';
                    }
                    $leftTopHtml .= ' <span class="live-badge">' . ($locale == 'ar' ? 'مباشر' : 'LIVE') . '</span>';
                    $leftSub = '';
                } elseif ($isFinished) {
                    $leftTop = $locale == 'ar' ? 'النهائية' : 'FT';
                    $leftSub = $dt ? $dt->translatedFormat('d/m') : '';
                } else {
                    if ($dt) {
                        if ($dt->isToday()) {
                            $leftTop = $locale == 'ar' ? 'اليوم' : 'Today';
                        } elseif ($dt->isTomorrow()) {
                            $leftTop = $locale == 'ar' ? 'غداً' : 'Tomorrow';
                        } else {
                            $leftTop = $dt->translatedFormat('d/m');
                        }
                        $leftSub = $dt->format('H:i');
                    } else {
                        $leftTop = $stateName ?: ($locale == 'ar' ? 'قريباً' : 'Soon');
                        $leftSub = '';
                    }
                }
            @endphp

            <div id="fixture-{{ $m->id }}" class="fixture-card fixture-card-v2 {{ $isLive ? 'is-live' : '' }}">
                <div class="fixture-meta">
                    <div class="meta-top js-left-top">{!! $leftTopHtml ?? e($leftTop) !!}</div>
                    <div class="meta-sub js-left-sub">{{ $leftSub }}</div>
                </div>

                <div class="fixture-divider"></div>

                <div class="fixture-body">
                    <div class="teams">
                        <div class="team-line mb-2">
                            @if ($homeLogo)
                                <img class="team-badge" src="{{ $homeLogo }}" alt="">
                            @else
                                <span class="team-badge" style="display:inline-block"></span>
                            @endif

                            @if ($homeId)
                                <a href="{{ route('club.show', ['teamId' => $homeId]) }}"
                                    class="team-name">{{ $homeName }}</a>
                            @else
                                <span class="team-name">{{ $home->name_ar }}</span>
                            @endif
                        </div>

                        <div class="team-line">
                            @if ($awayLogo)
                                <img class="team-badge" src="{{ $awayLogo }}" alt="">
                            @else
                                <span class="team-badge" style="display:inline-block"></span>
                            @endif

                            @if ($awayId)
                                <a href="{{ route('club.show', ['teamId' => $awayId]) }}"
                                    class="team-name">{{ $awayName }}</a>
                            @else
                                <span class="team-name">{{ $awayName }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="scorebox">
                        <span class="score-n js-home-score">{{ $hasScore ? $homeScore : '' }}</span>
                        <span class="score-sep">-</span>
                        <span class="score-n js-away-score">{{ $hasScore ? $awayScore : '' }}</span>
                        <span class="score-n muted js-no-score" style="{{ $hasScore ? 'display:none' : '' }}">—</span>
                    </div>
                </div>
            </div>

        @empty
            <div class="text-muted">{{ $locale == 'ar' ? 'لا توجد مباريات' : 'No fixtures found' }}</div>
        @endforelse
    </div>
</div>

@push('after-styles')
    <style>
        .nav-arrow {
            display: inline-flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #2b2d33;
            color: #fff;
            text-decoration: none
        }

        .nav-arrow.disabled {
            opacity: .35;
            pointer-events: none
        }

        .fixture-card-v2 {
            display: flex;
            background: #17191c;
            border: 1px solid rgba(255, 255, 255, .04);
            border-radius: 8px;
            overflow: hidden;
            min-height: 84px;
            align-items: stretch;
        }

        .fixture-card-v2 .fixture-meta {
            width: 110px;
            padding: 12px 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
            text-align: center;
        }

        .fixture-card-v2 .meta-top {
            font-weight: 800
        }

        .fixture-card-v2 .meta-sub {
            font-size: 12px;
            opacity: .85
        }

        .fixture-card-v2 .fixture-divider {
            width: 1px;
            background: rgba(255, 255, 255, .10)
        }

        .fixture-card-v2 .fixture-body {
            flex: 1;
            padding: 12px 16px;
            display: grid;
            grid-template-columns: 1fr 90px;
            align-items: center;
            gap: 12px;
        }

        .fixture-card-v2 .teams {
            display: flex;
            flex-direction: column;
            gap: 6px;
            text-align: right;
        }

        .fixture-card-v2 .team-line {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-end;
        }

        .fixture-card-v2 .team-badge {
            width: 28px;
            height: 28px;
            object-fit: contain;
            border-radius: 50%;
            background: transparent;
        }

        .fixture-card-v2 .team-name {
            font-weight: 600;
            font-size: 14px;
        }

        .fixture-card-v2 .scorebox {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 90px;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
        }

        .score-n {
            min-width: 26px;
            text-align: center;
            font-size: 18px;
        }

        .score-sep {
            opacity: .6;
            font-size: 16px;
        }

        .fixture-card-v2.is-live .meta-top {
            color: #22c55e;
        }

        .live-badge {
            display: inline-block;
            background: #16a34a;
            color: #fff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 6px;
        }

        .live-minute {
            display: block;
            background: transparent;
            color: #16a34a;
            font-weight: 900;
            font-size: 15px;
            line-height: 1;
            margin-bottom: 4px;
        }

        @media (max-width:480px) {
            .fixture-card-v2 {
                flex-direction: column
            }

            .fixture-card-v2 .fixture-meta {
                width: 100%;
                flex-direction: row;
                justify-content: space-between;
                align-items: center
            }

            .fixture-card-v2 .fixture-divider {
                width: 100%;
                height: 1px
            }

            .fixture-card-v2 .fixture-body {
                align-items: flex-start
            }
        }
    </style>
@endpush

@push('after-scripts')
    @if ($hasPossibleLive)
        <script>
            (function() {
                const POLL_URL = '{{ route('live.league', ['leagueId' => $leagueId]) }}';
                const LOCALE = '{{ $locale }}';

                function fmtMinute(m) {
                    if (m === null || m === undefined) return '';
                    m = parseInt(m, 10);
                    if (Number.isNaN(m)) return '';
                    if (m > 90) return '90+' + (m - 90) + "’";
                    return m + "’";
                }

                async function poll() {
                    try {
                        const res = await fetch(POLL_URL, {
                            cache: 'no-store'
                        });
                        if (!res.ok) return;

                        const json = await res.json();
                        if (!json || !json.ok) return;

                        (json.fixtures || []).forEach(fx => {
                            const el = document.getElementById('fixture-' + fx.id);
                            if (!el) return;

                            // scores
                            const homeEl = el.querySelector('.js-home-score');
                            const awayEl = el.querySelector('.js-away-score');
                            const noScore = el.querySelector('.js-no-score');

                            if (fx.home_score !== null && fx.away_score !== null) {
                                if (noScore) noScore.style.display = 'none';
                                if (homeEl) homeEl.textContent = fx.home_score;
                                if (awayEl) awayEl.textContent = fx.away_score;
                            } else {
                                if (noScore) noScore.style.display = '';
                                if (homeEl) homeEl.textContent = '';
                                if (awayEl) awayEl.textContent = '';
                            }

                            // live/meta
                            const leftTop = el.querySelector('.js-left-top');
                            const leftSub = el.querySelector('.js-left-sub');

                            const minuteText = fmtMinute(fx.minute ?? fx.computed_minute);

                            // force live style only for live returned fixtures
                            el.classList.add('is-live');
                            if (leftTop) leftTop.innerHTML =
                                (minuteText ? `<span class="live-minute">${minuteText.replace("’","")}</span>` :
                                    '') +
                                ` <span class="live-badge">${LOCALE === 'ar' ? 'مباشر' : 'LIVE'}</span>`;
                            if (leftSub) leftSub.textContent = '';
                        });

                    } catch (e) {
                        console.error('live poll error', e);
                    }
                }

                poll();
                setInterval(poll, 10000);
            })();
        </script>
    @endif
@endpush
