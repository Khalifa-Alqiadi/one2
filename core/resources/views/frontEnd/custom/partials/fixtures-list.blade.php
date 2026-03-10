@if (!empty($err))
    <div class="alert alert-warning">{{ $err }}</div>
@endif

<div class="fixtures-board">
    <div class="fixtures-grid">
        @forelse(($list ?? []) as $fx)
            @php
                // participants
                $participants = collect(data_get($fx, 'participants.data', data_get($fx, 'participants', [])));

                $home = $participants->first(fn($p) => data_get($p, 'meta.location') === 'home');
                $away = $participants->first(fn($p) => data_get($p, 'meta.location') === 'away');

                if (!$home || !$away) {
                    $home = $home ?: $participants->get(0);
                    $away = $away ?: $participants->get(1);
                }

                $homeId = data_get($home, 'id');
                $awayId = data_get($away, 'id');

                // state/date
                $stateName = (string) data_get($fx, 'state.name', '');
                $stateCode = strtolower((string) data_get($fx, 'state.code', ''));
                $lowerName = strtolower($stateName);

                $startAt = data_get($fx, 'starting_at');
                $dt = $startAt ? \Carbon\Carbon::parse($startAt) : null;

                // scores
                $scores = collect(data_get($fx, 'scores.data', data_get($fx, 'scores', [])));

                $homeScore = null;
                $awayScore = null;

                foreach ($scores as $s) {
                    $sc = data_get($s, 'score', []);

                    // form A
                    if (is_array($sc) && array_key_exists('home', $sc) && array_key_exists('away', $sc)) {
                        $homeScore = $sc['home'];
                        $awayScore = $sc['away'];
                        break;
                    }

                    // form B
                    $pid = data_get($s, 'participant_id');
                    $goals = data_get($sc, 'goals');

                    if ($pid && $goals !== null) {
                        if ($homeId && $pid == $homeId) {
                            $homeScore = $goals;
                        }
                        if ($awayId && $pid == $awayId) {
                            $awayScore = $goals;
                        }
                    }
                }

                $hasScore = is_numeric($homeScore) && is_numeric($awayScore);

                // minute + HT detection
                $minute = data_get($fx, 'minute') ?? data_get($fx, 'time.minute');

                if (!$minute) {
                    $periods = collect(data_get($fx, 'periods.data', data_get($fx, 'periods', [])));
                    $current = $periods->firstWhere('is_current', true) ?? $periods->firstWhere('current', true);
                    $minute = data_get($current, 'minutes', data_get($current, 'minute'));
                }

                // ✅ Half-time / Break detection (حسب state أو period name)
                $isHalfTime =
                    str_contains($stateCode, 'halftime') ||
                    str_contains($stateCode, 'ht') ||
                    str_contains($lowerName, 'half') ||
                    str_contains($lowerName, 'second') ||
                    str_contains($lowerName, 'النصف') ||
                    str_contains($lowerName, 'نصف') ||
                    str_contains($stateName, 'منتصف') ||
                    str_contains($stateName, 'استراحة');

                // statuses
                $isFinished =
                    str_contains($stateCode, 'finished') ||
                    str_contains($stateCode, 'ft') ||
                    str_contains($lowerName, 'finished') ||
                    str_contains($stateName, 'انتهت') ||
                    str_contains($stateName, 'النه');

                $isLive =
                    str_contains($stateCode, 'live') ||
                    str_contains($stateCode, 'inplay') ||
                    str_contains($lowerName, 'live') ||
                    str_contains($stateName, 'مباشر');

                // treat half-time as live for badge/display purposes
                if ($isHalfTime) {
                    $isLive = true;
                }

                $isPostponed =
                    str_contains($stateCode, 'postpon') ||
                    str_contains($lowerName, 'postpon') ||
                    str_contains($stateName, 'مؤجل');

                $isCanceled =
                    str_contains($stateCode, 'cancel') ||
                    str_contains($lowerName, 'cancel') ||
                    str_contains($stateName, 'ملغ');

                // left labels
                $leftTop = '';
                $leftSub = '';
                $isLiveClass = false;

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
                } elseif ($isHalfTime) {
                    // Treat half-time as live: show "مباشر" and minute in green
                    $leftTop = $locale == 'ar' ? 'مباشر' : 'LIVE';
                    $leftSub = $minute ? $minute . '’' : ($locale == 'ar' ? 'الآن' : 'Now');
                    $isLiveClass = true;
                } elseif ($isLive) {
                    $leftTop = $locale == 'ar' ? 'مباشر' : 'LIVE';
                    $leftSub = $minute ? $minute . '’' : ($locale == 'ar' ? 'الآن' : 'Now');
                    $isLiveClass = true;
                } elseif ($isFinished) {
                    // Show finished as "منتهي" and show the match start time
                    $leftTop = $locale == 'ar' ? 'منتهي' : 'FT';
                    $leftSub = $dt ? $dt->translatedFormat('d/m H:i') : '';
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

              <div class="fixture-card fixture-card-v2 {{ $isLiveClass ? 'is-live' : '' }} {{ $locale == 'ar' ? 'rtl' : '' }}"
                  data-fixture-id="{{ data_get($fx, 'id') }}"
                  id="fixture-{{ data_get($fx, 'id') }}"
                  data-state-code="{{ $stateCode }}"
                  data-state-name="{{ $stateName }}"
                  data-minute="{{ $minute }}"
                  data-is-live="{{ $isLive ? '1' : '0' }}"
                  data-display-top="{{ e($leftTop) }}"
                  data-display-sub="{{ e($leftSub) }}"
              >
                <div class="fixture-meta">
                    <div class="meta-top js-left-top">{{ $leftTop }}
                        @if($isLive)
                            <span class="live-badge js-live-badge">{{ $locale == 'ar' ? 'مباشر' : 'LIVE' }}</span>
                        @endif
                    </div>
                    <div class="meta-sub js-left-sub">{{ $leftSub }}</div>
                </div>

                <div class="fixture-divider"></div>

                <div class="fixture-body">
                    <div class="teams">
                        <div class="team-line mb-2">
                            <img class="team-badge" src="{{ data_get($home, 'image_path', '') }}" alt="">
                            @if ($homeId)
                                <a href="{{ route('club.show', ['teamId' => $homeId]) }}" class="team-name {{ $locale == 'ar' ? 'text-end' : '' }}">
                                    {{ data_get($home, 'name', $locale == 'ar' ? 'المضيف' : 'Home') }}
                                </a>
                            @else
                                <span class="team-name {{ $locale == 'ar' ? 'text-end' : '' }}">{{ data_get($home, 'name', $locale == 'ar' ? 'المضيف' : 'Home') }}</span>
                            @endif
                        </div>

                        <div class="team-line">
                            <img class="team-badge" src="{{ data_get($away, 'image_path', '') }}" alt="">
                            @if ($awayId)
                                <a href="{{ route('club.show', ['teamId' => $awayId]) }}" class="team-name {{ $locale == 'ar' ? 'text-end' : '' }}">
                                    {{ data_get($away, 'name', $locale == 'ar' ? 'الضيف' : 'Away') }}
                                </a>
                            @else
                                <span class="team-name {{ $locale == 'ar' ? 'text-end' : '' }}">{{ data_get($away, 'name', $locale == 'ar' ? 'الضيف' : 'Away') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="scorebox">
                        @if ($hasScore)
                            <span class="score-n js-home-score">{{ $homeScore }}</span>
                            <span class="score-sep">-</span>
                            <span class="score-n js-away-score">{{ $awayScore }}</span>
                        @else
                            <span class="score-n muted js-no-score">—</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted">{{ $locale == 'ar' ? 'لا توجد مباريات' : 'No fixtures found' }}</div>
        @endforelse
    </div>

    {{-- ✅ Pagination --}}
    {{-- ✅ Pagination (Numbers) --}}
    @php
        $current = (int) data_get($pager, 'current_page', 1);
        $last = (int) data_get($pager, 'last_page', 1);

        // window size = 5 pages around current
        $window = 5;
        $half = intdiv($window, 2);

        $startP = max(1, $current - $half);
        $endP = min($last, $startP + $window - 1);

        // adjust start if we hit the end
        $startP = max(1, $endP - $window + 1);
    @endphp

    @if ($last > 1)
        <div class="d-flex align-items-center justify-content-center gap-2 mt-3 flex-wrap">

            {{-- Prev --}}
            @if ($current > 1)
                <a class="nav-arrow" href="{{ request()->fullUrlWithQuery([$pageKey => $current - 1]) }}">‹</a>
            @else
                <span class="nav-arrow disabled">‹</span>
            @endif

            {{-- First --}}
            <a class="nav-arrow {{ $current == 1 ? 'active-page' : '' }}"
                href="{{ request()->fullUrlWithQuery([$pageKey => 1]) }}">1</a>

            {{-- Dots left --}}
            @if ($startP > 2)
                <span class="text-muted px-1">…</span>
            @endif

            {{-- Middle window --}}
            @for ($p = $startP; $p <= $endP; $p++)
                @continue($p == 1 || $p == $last)
                <a class="nav-arrow {{ $current == $p ? 'active-page' : '' }}"
                    href="{{ request()->fullUrlWithQuery([$pageKey => $p]) }}">
                    {{ $p }}
                </a>
            @endfor

            {{-- Dots right --}}
            @if ($endP < $last - 1)
                <span class="text-muted px-1">…</span>
            @endif

            {{-- Last --}}
            @if ($last > 1)
                <a class="nav-arrow {{ $current == $last ? 'active-page' : '' }}"
                    href="{{ request()->fullUrlWithQuery([$pageKey => $last]) }}">{{ $last }}</a>
            @endif

            {{-- Next --}}
            @if ($current < $last)
                <a class="nav-arrow" href="{{ request()->fullUrlWithQuery([$pageKey => $current + 1]) }}">›</a>
            @else
                <span class="nav-arrow disabled">›</span>
            @endif

            {{-- Small info --}}
            <span class="text-muted small ms-2">
                {{ $current }} / {{ $last }}
            </span>
        </div>
    @endif
</div>
