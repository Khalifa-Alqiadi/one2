@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp


@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">
        <div class="container">
            <div class="section-title text-start mb-4">
                <h2 class="d-flex align-items-center gap-2">
                    <img src="{{ URL::to('uploads/settings/Vector.svg') }}" alt="">
                    {{ __('frontend.matches') }}
                </h2>
            </div>
            @php
                $locale = $locale ?? 'ar';
                $activeTab = $activeTab ?? 'today';
            @endphp

            <div class="tabs-wrapper d-flex mb-5">
                @foreach ($dates as $day)
                    <a href="{{ route('matches', ['date' => $day['key']]) }}"
                        class="tab-item {{ $activeTab === $day['key'] ? 'active' : '' }}" data-date="{{ $day['key'] }}">

                        <div class="tab-label">{{ $day['label'] }}</div>
                        <div class="tab-date">{{ $day['date'] }}</div>
                    </a>
                @endforeach
            </div>


            <div class="matches matches-home">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($matches as $match)
                        <?php
                        $isFinished = (bool) $match->is_finished;
                        $timezone = env('TIMEZONE', 'UTC');
                        $isTimeLive = false;
                        if (!$isFinished && $match->starting_at) {
                            try {
                                $start = \Carbon\Carbon::parse($match->starting_at);
                                $isTimeLive = now()->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
                            } catch (\Throwable $e) {
                            }
                        }

                        $dt = $match->starting_at ? \Carbon\Carbon::parse($match->starting_at)->timezone($timezone) : null;
                        $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                        $timeLabel = $dt ? $dt->format('H:i') : '';

                        $minute = is_numeric($match->minute) ? (int) $match->minute : null;
                        ?>
                        <div class="col mb-3">
                            <div class="card bg-transparent gx-fixture-card" id="fixture-{{ $match->id }}"
                                data-live="{{ $isTimeLive ? 1 : 0 }}">
                                <div
                                    class="card-header d-flex align-items-center justify-content-between bg-transparent border-0 p-0 mb-3">
                                    @if ($match->league)
                                        <span>{{ $match->league->$name_var }}</span>
                                    @endif
                                    <span class="js-minute">
                                        @if ($isTimeLive && $minute)
                                            {{ $minute }}
                                        @endif
                                    </span>
                                </div>
                                <div class="box-match d-flex justify-content-between align-items-center ">

                                    <div class="team d-flex flex-column align-items-center">
                                        @if ($match->homeTeam)
                                            @if ($match->homeTeam->image_path)
                                                <div class="image d-flex align-items-center justify-content-center">
                                                    <img src="{{ $match->homeTeam->image_path }}" style="height:30px"
                                                        alt="">
                                                </div>
                                            @endif
                                            <span class="mt-2 text-center">{{ $match->homeTeam->$name_var }}</span>
                                        @endif
                                    </div>
                                    <div class="details text-center">
                                        @if ($isFinished || $isTimeLive)
                                            <div
                                                class="d-flex gx-score align-items-center justify-content-center gap-2">
                                                <div class="goals js-home-score">
                                                    <span class="">{{ $match->home_score }}</span>
                                                </div>
                                                <span class="m-x-sm">-</span>
                                                <div class="goals js-away-score">
                                                    <span class="">{{ $match->away_score }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div>
                                                <span class="m-x-sm">vs</span>
                                            </div>
                                        @endif
                                        <div class="status">
                                            <span class="js-live-badge">
                                                @if ($isFinished)
                                                    {{ __('frontend.finished') }}
                                                @elseif ($isTimeLive)
                                                    {{ __('frontend.live') }}
                                                @else
                                                    {{ __('frontend.not_started') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="team d-flex flex-column align-items-center">
                                        @if ($match->awayTeam)
                                            @if ($match->awayTeam->image_path)
                                                <div class="image d-flex align-items-center justify-content-center">
                                                    <img src="{{ $match->awayTeam->image_path }}" style="height:30px"
                                                        alt="">
                                                </div>
                                            @endif
                                            <span class="mt-2 text-center">{{ $match->awayTeam->$name_var }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div
                                    class="card-body border-top mt-3 pb-0 d-flex align-items-center justify-content-between">
                                    <span>{!! Helper::day_name($match->starting_at) !!}</span>
                                    <a href="{{ route('match.show', ['id' => $match->id]) }}">
                                        {{ __('frontend.match_show') }}
                                        <i class="fas fa-arrow-{{ Helper::isRTL() ? 'left' : 'right' }} mx-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                return Array.from(
                    document.querySelectorAll('[id^="fixture-"][data-live="1"]'),
                );
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
