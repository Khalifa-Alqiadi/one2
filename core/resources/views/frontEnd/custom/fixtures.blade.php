@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">

        <div class="container">


            {{-- Tabs --}}
            <ul class="nav nav-pills league-pills mb-4 px-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? 'today') == 'yesterday' ? 'active' : '' }}"
                        href="{{ request()->fullUrlWithQuery(['tab' => 'yesterday']) }}">
                        {{ __('frontend.yesterdays_matches') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? 'today') == 'today' ? 'active' : '' }}"
                        href="{{ request()->fullUrlWithQuery(['tab' => 'today']) }}">
                        {{ __('frontend.todays_matches') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? 'today') == 'tomorrow' ? 'active' : '' }}"
                        href="{{ request()->fullUrlWithQuery(['tab' => 'tomorrow']) }}">
                        {{ $locale == 'ar' ? 'مباريات غدا' : 'Tomorrow' }}
                    </a>
                </li>
            </ul>

            <div class="tab-content cardx border-0 p-3 px-0">

                {{-- Yesterday --}}
                <div class="tab-pane fade {{ ($activeTab ?? 'today') == 'yesterday' ? 'show active' : '' }}"
                    id="t-yesterday">
                    @include('frontEnd.custom.partials.fixtures-list', [
                        'list' => $yesterdays_matches,
                        'err' => $yestErr,
                        'pager' => $p_yest,
                        'pageKey' => 'p_yest',
                    ])
                </div>

                {{-- Today --}}
                <div class="tab-pane fade {{ ($activeTab ?? 'today') == 'today' ? 'show active' : '' }}" id="t-today">
                    @include('frontEnd.custom.partials.fixtures-list', [
                        'list' => $todays_matches,
                        'err' => $todayErr,
                        'pager' => $p_today,
                        'pageKey' => 'p_today',
                    ])
                </div>

                {{-- Tomorrow --}}
                <div class="tab-pane fade {{ ($activeTab ?? 'today') == 'tomorrow' ? 'show active' : '' }}"
                    id="t-tomorrow">
                    @include('frontEnd.custom.partials.fixtures-list', [
                        'list' => $tomorrows_matches,
                        'err' => $tomErr,
                        'pager' => $p_tom,
                        'pageKey' => 'p_tom',
                    ])
                </div>

            </div>



        </div>



    </section>
@endsection
@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            const POLL_URL = '{{ route('matches.today.json') }}';
            const LOCALE = '{{ $locale }}';

            async function fetchAndUpdate(){
                try{
                    const res = await fetch(POLL_URL, { cache: 'no-store' });
                    if(!res.ok) return;
                    const json = await res.json();
                    console.debug('fixtures.poll', json);
                    // update small status box
                    const st = document.getElementById('fixtures-poll-status');
                    if (st) st.textContent = (new Date()).toLocaleTimeString() + ' — OK';
                    if(!json || !json.ok) return;

                    (json.fixtures || []).forEach(fx => {
                        const el = document.getElementById('fixture-' + fx.id);
                        if(!el) return;

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

                        const leftTop = el.querySelector('.js-left-top');
                        const leftSub = el.querySelector('.js-left-sub');

                        // prefer server-provided display labels; prefer computed_minute if minute missing
                        const top = fx.display_top ?? (fx.state && fx.state.name ? fx.state.name : '');
                        const minuteVal = (fx.minute !== null && fx.minute !== undefined) ? fx.minute : (fx.computed_minute !== null && fx.computed_minute !== undefined ? fx.computed_minute : null);
                        const sub = fx.display_sub ?? (minuteVal ? (minuteVal + "’") : '');

                        if (fx.is_live) {
                            el.classList.add('is-live');
                            if (leftTop) leftTop.innerHTML = top + ' <span class="live-badge js-live-badge">' + (LOCALE == 'ar' ? 'مباشر' : 'LIVE') + '</span>';
                            if (leftSub) leftSub.textContent = sub;
                        } else {
                            el.classList.remove('is-live');
                            if (leftTop) leftTop.textContent = top;
                            if (leftSub) leftSub.textContent = sub;
                        }
                    });
                }catch(e){
                    console.error('Fixtures poll error', e);
                    const st = document.getElementById('fixtures-poll-status');
                    if (st) st.textContent = (new Date()).toLocaleTimeString() + ' — ERROR';
                }
            }

            // initial + every 10s
            fetchAndUpdate();
            setInterval(fetchAndUpdate, 10000);
        })();
    </script>
@endpush
@push('after-styles')
    <style>
        #fixtures-poll-status{
            position:fixed;
            right:12px;
            bottom:12px;
            background:rgba(0,0,0,0.6);
            color:#bcd;
            padding:6px 8px;
            border-radius:6px;
            font-size:12px;
            z-index:9999;
        }
    </style>
@endpush

@push('after-scripts')
    <script>
        // small DOM element for debug status
        (function(){
            if (!document.getElementById('fixtures-poll-status')){
                const el = document.createElement('div');
                el.id = 'fixtures-poll-status';
                el.textContent = 'poll: —';
                document.body.appendChild(el);
            }
        })();
    </script>
@endpush
@push('after-styles')
    <style>
        /* ====== Theme ====== */
        #content {
            margin-top: 140px !important;
        }

        @media (max-width: 992px) {
            #content {
                margin-top: 120px !important;
            }
        }
    </style>
    <style>
        .nav-arrow.active-page{
            background:#3b82f6;
            font-weight:900;
        }

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
            background: #24262b;
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 14px;
            overflow: hidden;
            min-height: 92px
        }

        .fixture-card-v2 .fixture-meta {
            width: 140px;
            padding: 14px 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 6px
        }

        .fixture-card-v2.rtl {
            direction: rtl;
        }

        .fixture-card-v2.rtl .fixture-meta {
            text-align: right;
            padding-right: 16px;
            padding-left: 8px;
        }

        .fixture-card-v2 .meta-top {
            font-weight: 800;
            display:flex;
            align-items:center;
            gap:8px;
        }

        .fixture-card-v2 .meta-top small.minute {
            color: #22c55e;
            font-size: 13px;
            font-weight: 800;
            display:block;
            margin-top:4px;
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
            padding: 14px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .fixture-card-v2 .teams {
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .fixture-card-v2 .scorebox {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 70px;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
        }

        .score-n {
            min-width: 18px;
            text-align: center
        }

        .score-sep {
            opacity: .6
        }

        .fixture-card-v2.is-live .meta-top {
            color: #22c55e
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
        .team-name { display:block }
        .team-name.text-end { text-align: right }
    </style>
    <style>
        .live-badge{
            display:inline-block;
            background:#22c55e;
            color:#fff;
            font-size:11px;
            padding:2px 6px;
            border-radius:6px;
            margin-left:6px;
            vertical-align:middle;
        }
    </style>
@endpush
