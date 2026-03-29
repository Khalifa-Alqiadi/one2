@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp

@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">
        <div class="container">
            @php
                $locale = $locale ?? 'ar';
                $activeTab = $activeTab ?? 'today';
            @endphp

            <ul class="nav nav-pills league-pills mb-4 px-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'yesterday' ? 'active' : '' }}"
                        href="{{ route('matches', ['tab' => 'yesterday']) }}">
                        {{ __('frontend.yesterdays_matches') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'today' ? 'active' : '' }}"
                        href="{{ route('matches', ['tab' => 'today']) }}">
                        {{ __('frontend.todays_matches') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'tomorrow' ? 'active' : '' }}"
                        href="{{ route('matches', ['tab' => 'tomorrow']) }}">
                        {{ $locale == 'ar' ? 'مباريات غدا' : 'Tomorrow' }}
                    </a>
                </li>
            </ul>

            <div class="tab-content cardx border-0 p-3 px-0">
                <div class="tab-pane fade show active" id="{{ $activeTab }}" role="tabpanel">
                    <div class="gx-fixtures-grid">
                        <div class="row">
                            @forelse ($fixtures as $fx)
                                @php
                                    $isFinished = (bool) $fx->is_finished;

                                    $isTimeLive = false;
                                    if (!$isFinished && $fx->starting_at) {
                                        try {
                                            $start = \Carbon\Carbon::parse($fx->starting_at);
                                            $isTimeLive = now()->between(
                                                $start->copy()->subMinutes(15),
                                                $start->copy()->addHours(3),
                                            );
                                        } catch (\Throwable $e) {
                                            $isTimeLive = false;
                                        }
                                    }

                                    $home = $fx->homeTeam;
                                    $away = $fx->awayTeam;

                                    $homeName =
                                        $locale == 'ar'
                                            ? $home->name_ar ?? $home->name_en
                                            : $home->name_en ?? $home->name_ar;

                                    $awayName =
                                        $locale == 'ar'
                                            ? $away->name_ar ?? $away->name_en
                                            : $away->name_en ?? $away->name_ar;

                                    $timezone = env('TIMEZONE', 'UTC');

                                    $dt = $fx->starting_at
                                        ? \Carbon\Carbon::parse($fx->starting_at)->timezone($timezone)
                                        : null;

                                    $timeLabel = $dt ? $dt->format('H:i') : '';

                                    $homeScore = is_numeric($fx->home_score) ? (int) $fx->home_score : null;
                                    $awayScore = is_numeric($fx->away_score) ? (int) $fx->away_score : null;

                                    $minute = is_numeric($fx->minute) ? (int) $fx->minute : null;
                                @endphp

                                <div class="col-md-6">
                                    <a href="{{ route('match.show', ['id' => $fx->id]) }}" class="gx-fixture-card"
                                        id="fixture-{{ $fx->id }}" data-live="{{ $isTimeLive ? 1 : 0 }}"
                                        data-last-home="{{ $homeScore !== null ? $homeScore : '' }}"
                                        data-last-away="{{ $awayScore !== null ? $awayScore : '' }}" data-last-min="">
                                        <p class="league">{{ $fx->league->$name_var ?? '' }}</p>

                                        <div class="match">
                                            <div class="gx-left">
                                                <div class="gx-status">
                                                    <span class="js-live-badge">
                                                        @if ($isFinished)
                                                            {{ $locale == 'ar' ? 'النهائية' : 'FT' }}
                                                        @elseif ($isTimeLive)
                                                            {{ $locale == 'ar' ? 'مباشر' : 'LIVE' }}
                                                        @else
                                                            {{ $locale == 'ar' ? 'لم تبدأ' : 'Not Started' }}
                                                        @endif
                                                    </span>

                                                    <span class="js-minute">
                                                        @if ($isTimeLive && $minute)
                                                            {{ $minute }}'
                                                        @endif
                                                    </span>

                                                    <span class="gx-datetime d-flex">
                                                        {!! Helper::day_name($dt) !!}
                                                        @if ($timeLabel)
                                                            • {{ $timeLabel }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="gx-score">
                                                <span
                                                    class="js-home-score">{{ $homeScore !== null ? $homeScore : '-' }}</span>
                                                <span class="gx-dash">-</span>
                                                <span
                                                    class="js-away-score">{{ $awayScore !== null ? $awayScore : '-' }}</span>
                                            </div>

                                            <div class="gx-teams">
                                                <div class="gx-team-row">
                                                    <img class="gx-team-logo" src="{{ $home->image_path ?? '' }}"
                                                        alt="">
                                                    <span class="gx-team-name">{{ $homeName }}</span>
                                                </div>

                                                <div class="gx-team-row">
                                                    <img class="gx-team-logo" src="{{ $away->image_path ?? '' }}"
                                                        alt="">
                                                    <span class="gx-team-name">{{ $awayName }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light text-center mb-0">
                                        {{ $locale == 'ar' ? 'لا توجد مباريات' : 'No matches found' }}
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                {!! $fixtures->appends(request()->query())->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script>
        (function() {
            const URL = "{{ route('fixtures.live.proxy') }}";
            let inflight = false;

            function liveEls() {
                return Array.from(document.querySelectorAll('.gx-fixture-card[data-live="1"]'));
            }

            function liveIds() {
                return liveEls()
                    .map(el => parseInt(el.id.replace('fixture-', ''), 10))
                    .filter(id => !Number.isNaN(id));
            }

            function fmtMinute(m, stateCode = "") {
                if (m === null || m === undefined || m === "") return "";
                m = parseInt(m, 10);
                if (Number.isNaN(m)) return "";

                if (m > 90) return `90+${m - 90}'`;
                if (m > 45 && stateCode === "INPLAY_1ST_FT") return `45+${m - 45}'`;

                return `${m}'`;
            }

            function setText(el, selector, value, allowEmpty = false) {
                const node = el.querySelector(selector);
                if (!node) return;

                if (value === null || value === undefined) {
                    if (allowEmpty) node.textContent = "";
                    return;
                }

                if (!allowEmpty && (value === "-" || value === "")) return;

                node.textContent = value;
            }

            async function tick() {
                if (inflight) return;

                const ids = liveIds();
                if (!ids.length) return;

                inflight = true;

                try {
                    const res = await fetch(URL + "?ids=" + encodeURIComponent(ids.join(",")), {
                        cache: "no-store",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    });

                    if (!res.ok) return;

                    const json = await res.json();
                    if (!json || !json.ok || !Array.isArray(json.fixtures)) return;

                    json.fixtures.forEach((fx) => {
                        const el = document.getElementById("fixture-" + fx.id);
                        if (!el) return;

                        const lastHome = el.dataset.lastHome ?? "";
                        const lastAway = el.dataset.lastAway ?? "";
                        const lastMin = el.dataset.lastMin ?? "";

                        if (fx.home_score !== null && fx.home_score !== undefined && fx.home_score !== "") {
                            setText(el, ".js-home-score", fx.home_score);
                            el.dataset.lastHome = fx.home_score;
                        } else if (lastHome !== "") {
                            setText(el, ".js-home-score", lastHome);
                        }

                        if (fx.away_score !== null && fx.away_score !== undefined && fx.away_score !== "") {
                            setText(el, ".js-away-score", fx.away_score);
                            el.dataset.lastAway = fx.away_score;
                        } else if (lastAway !== "") {
                            setText(el, ".js-away-score", lastAway);
                        }

                        if (["FT", "AET", "PEN"].includes(fx.state_code) || fx.is_finished) {
                            el.dataset.live = "0";
                            setText(el, ".js-live-badge", "{{ $locale == 'ar' ? 'النهائية' : 'FT' }}",
                                true);
                            setText(el, ".js-minute", "", true);
                            el.dataset.lastMin = "";
                            return;
                        }

                        if (fx.state_code === "NS") {
                            el.dataset.live = "0";
                            setText(el, ".js-live-badge",
                                "{{ $locale == 'ar' ? 'لم تبدأ' : 'Not Started' }}", true);
                            setText(el, ".js-minute", "", true);
                            el.dataset.lastMin = "";
                            return;
                        }

                        el.dataset.live = "1";

                        if (fx.state_code === "HT") {
                            setText(el, ".js-live-badge",
                                "{{ $locale == 'ar' ? 'منتصف المباراة' : 'HT' }}", true);
                            setText(el, ".js-minute", "", true);
                            el.dataset.lastMin = "";
                            return;
                        }

                        setText(el, ".js-live-badge", "{{ $locale == 'ar' ? 'مباشر' : 'LIVE' }}", true);

                        if (fx.minute !== null && fx.minute !== undefined && fx.minute !== "") {
                            const minuteText = fmtMinute(fx.minute, fx.state_code);
                            if (minuteText) {
                                setText(el, ".js-minute", minuteText, true);
                                el.dataset.lastMin = minuteText;
                            }
                        } else if (lastMin !== "") {
                            setText(el, ".js-minute", lastMin, true);
                        } else {
                            setText(el, ".js-minute", "", true);
                        }
                    });

                } catch (e) {
                    console.error("Live fixtures error:", e);
                } finally {
                    inflight = false;
                }
            }

            tick();
            setInterval(tick, 5000);
        })();
    </script>
@endpush
