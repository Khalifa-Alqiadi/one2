@php
    $isRtl = ($locale ?? 'ar') === 'ar';
    $name_var = 'name_' . @Helper::currentLanguage()->code;

@endphp
@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football football-match details-match" style="margin-top: 100px">
        <div class="container my-4" style="direction: {{ $isRtl ? 'rtl' : 'ltr' }};">
            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('league.rounds', ['id' => $fixture->league->id]) }}" class="league-header mb-3">
                        @if (data_get($fixture->league, 'image_path'))
                            <div class="logo rounded-circle bg-white">
                                <img src="{{ data_get($fixture->league, 'image_path') }}" alt="">
                            </div>
                        @endif
                        <h4 class="mb-0 fw-bold">{{ data_get($fixture->league, $name_var, 'League') }}</h4>
                    </a>
                </div>
            </div>
            <div class="row justify-content-center row-details">

                {{-- Score --}}
                @php
                    $isFinished = (bool) $fixture->is_finished;
                    $isTimeLive = false;
                    if (!$isFinished && $fixture->starting_at) {
                        try {
                            $start = \Carbon\Carbon::parse($fixture->starting_at);
                            $isTimeLive = now()->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
                        } catch (\Throwable $e) {
                        }
                    }
                    $status = $fx['status'] ?? 'NS';
                    $state_code = $fx['state_code'] ?? 'NS';
                    $startAt = $fx['starting_at'] ?? null;
                    $dt = $startAt ? \Carbon\Carbon::parse($startAt)->timezone(Helper::getUserTimezone()) : null;
                    $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                    $timeLabel = $dt ? $dt->format('H:i') : '';
                @endphp

                <div class="col-lg-8 mb-4">
                    {{-- Header Card --}}
                    <div class="card bg-dark text-light border-0 shadow-sm mb-3" style="border-radius:14px;">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="text-muted text-center small d-block d-md-none mb-3">
                                        <span>
                                            {!! Helper::day_name($dt, true) !!}
                                        </span>
                                    </div>
                                </div>
                                {{-- Home --}}
                                <div class="col-5">
                                    <div class="text-center gap-2 team-details">
                                        <img src="{{ $fixture->homeTeam->image_path ?? '' }}"
                                            class=" rounded-circle object-contain p-1">
                                        <h4 class="fw-bold mt-4">{{ $fixture->homeTeam->$name_var ?? '-' }}</h4>
                                    </div>
                                    @include('frontEnd.football.partials.top-events', [
                                        'teamid' => $fixture->homeTeam->id,
                                        'textDiration' => 'text-start',
                                    ])
                                </div>



                                {{-- Score / Kickoff --}}
                                <div class="col-2">
                                    <div class="text-center">
                                        {{-- ✅ Box: Score (LIVE/HT/FT) --}}
                                        {{-- @if ($isFinished || $isTimeLive) --}}
                                        <div class="js-scorebox"
                                            style="font-size:28px;font-weight:800;letter-spacing:1px; display: {{ $state_code !== 'NS' ? 'flex' : 'none' }}; align-items:center; justify-content:center; gap:10px;">
                                            <span class="js-home fw-bold fs-2">{{ $fixture->home_score ?? '-' }}</span>
                                            <span class="mx-0 mx-md-3" style="opacity:.6">-</span>
                                            <span class="js-away fw-bold fs-2">{{ $fixture->away_score ?? '-' }}</span>
                                        </div>
                                        {{-- @endif --}}

                                        {{-- ✅ Box: Not started (NS) --}}
                                        <div class="js-kickoffbox">
                                            <div class="fw-bold"
                                                style="font-size:14px; opacity:.95;
                                                display: {{ $state_code === 'NS' ? 'block' : 'none' }};">
                                                {{ __('frontend.not_started') }}</div>
                                        </div>

                                        <div class="small mt-2">
                                            <span class="badge bg-success js-status"
                                                style="display: {{ $state_code === 'LIVE' || $state_code === 'INPLAY_2ND' || $state_code === 'INPLAY_1ST' ? 'inline-block' : 'none' }};">
                                                {{ __('frontend.live') }}
                                            </span>

                                            <span class="badge fs-6 text-secondary js-ns"
                                                style="display: {{ $state_code === 'NS' ? 'inline-block' : 'none' }};">{{ __('frontend.not_started') }}</span>

                                            <span class="badge fs-6 text-secondary js-ht"
                                                style="display: {{ $state_code === 'HT' ? 'inline-block' : 'none' }};">{{ __('frontend.half_time') }}</span>

                                            <span class="badge fs-6 text-secondary js-ft"
                                                style="display: {{ $state_code === 'FT' ? 'inline-block' : 'none' }};">{{ __('frontend.finished') }}</span>

                                            <span class="text-success fw-bold js-minute">
                                                {{ $status === 'LIVE' && !empty($fx['minute']) ? $fx['minute'] . "'" : '' }}
                                            </span>
                                        </div>
                                        <div class="text-muted small d-none d-md-block mt-3">
                                            <span>
                                                {!! Helper::day_name($dt, true) !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Away --}}
                                <div class="col-5">
                                    <div class="text-center gap-2 team-details">
                                        <img src="{{ $fixture->awayTeam->image_path ?? '' }}"
                                            class=" rounded-circle object-contain p-1">
                                        <h4 class="fw-bold mt-3">{{ $fixture->awayTeam->$name_var ?? '-' }}</h4>
                                    </div>
                                    @include('frontEnd.football.partials.top-events', [
                                        'teamid' => $fixture->awayTeam->id,
                                        'textDiration' => 'text-end',
                                    ])
                                </div>
                            </div>
                        </div>

                    </div>
                    @php
                        $status = $fx['status'] ?? 'NS';
                    @endphp
                    {{-- Tabs --}}
                    <ul class="nav nav-tabs nav-fill mb-3 league-pills" role="tablist"
                        style="border-color: rgba(255,255,255,.08);">
                        @if ($state_code !== 'NS')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-events" type="button"
                                    role="tab">{{ __('frontend.events') }}</button>
                            </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-stats" type="button"
                                role="tab">{{ __('frontend.statistics') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " data-bs-toggle="tab" data-bs-target="#t-lineups" type="button"
                                role="tab">{{ __('frontend.lineups') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-standings" type="button">
                                {{ __('frontend.standings') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-commentary" type="button">
                                {{ __('frontend.discussion') }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- EVENTS --}}
                        @include('frontEnd.football.partials.events') {{-- هذا ملف جديد خاص بأحداث المباراة (أفضل من وضع الكود هنا مباشرة) --}}

                        {{-- STATISTICS --}}
                        @include('frontEnd.football.partials.statistics') {{-- هذا ملف جديد خاص بإحصائيات المباراة (أفضل من وضع الكود هنا مباشرة) --}}

                        {{-- LINEUPS --}}
                        @include('frontEnd.football.partials.lineups')

                        @include('frontEnd.football.rounds-tabs.standings', [
                            'standings' => $standings,
                            'homeID' => $fixture->homeTeam->id,
                            'awayID' => $fixture->awayTeam->id,
                        ])

                        @include('frontEnd.football.partials.discussion')

                    </div>

                    {{-- ✅ polling للتفاصيل لو LIVE --}}
                    @if (($fx['status'] ?? '') === 'LIVE')
                        @push('after-scripts')
                            <script>
                                (function() {
                                    const url = "{{ route('match.show', ['id' => $fixtureId]) }}?refresh=1";
                                    async function tick() {
                                        try {
                                            const res = await fetch(url, {
                                                cache: 'no-store'
                                            });
                                            if (!res.ok) return;

                                            // نجيب الصفحة ونستخرج JSON؟ (أسهل حل: سو API endpoint يرجع JSON)
                                            // ✅ الأفضل: تعمل endpoint JSON خاص للتفاصيل (أعطيك تحت)
                                        } catch (e) {}
                                    }
                                    // هنا اتركه مع endpoint JSON (أفضل)
                                })();
                            </script>
                        @endpush
                    @endif
                    {{-- @endif --}}
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="">
                        {{-- ✅ Box: TV Stations --}}
                        @if (!$isFinished)
                            <div class="card bg-dark text-light shadow-sm mb-3" style="border-radius:14px;">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">{{ __('frontend.tv_stations') }}</h5>

                                    @php
                                        $stations = collect($fx['tv_stations'] ?? [])
                                            ->filter(fn($station) => is_array($station))
                                            ->unique(fn($station) => strtolower(trim($station['name'] ?? '')))
                                            ->values();
                                    @endphp

                                    @if ($stations->isNotEmpty())
                                        <ul class="list-unstyled mb-0 px-2">
                                            @foreach ($stations as $station)
                                                @if (!empty($station['url']))
                                                    <li class="mb-2 border-bottom pb-2 border-secondary">
                                                        <a href="{{ $station['url'] }}" target="_blank">
                                                            @if (!empty($station['image']))
                                                                <img src="{{ $station['image'] }}" alt="station Image"
                                                                    class="ms-2"
                                                                    style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                                                            @else
                                                                <i class="fas fa-tv me-2"
                                                                    style="color:rgba(255,255,255,.6);"></i>
                                                            @endif

                                                            {{ $station['name'] ?? __('frontend.unknown_station') }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-muted">{{ __('frontend.no_tv_stations') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        {{-- ✅ Box: Match Info (الملعب، المدينة، السعة) --}}
                        <div class="card bg-dark text-light shadow-sm mb-3" style="border-radius:14px;">
                            <div class="card-body">
                                <h5 class="card-title mb-3">{{ __('frontend.match_info') }}</h5>
                                <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
                                    @if (!empty($fixture->league->image_path))
                                        <img src="{{ $fixture->league->image_path }}" alt="League Image " class="p-1 "
                                            style="width:50px;height:50px;border-radius:8px;object-fit:cover;background:rgba(255,255,255,.1)">
                                    @else
                                        <div
                                            style="width:50px;height:50px;border-radius:8px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-trophy" style="color:rgba(255,255,255,.6);"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">
                                            {{ data_get($fixture->league, $name_var, 'League') ?? __('frontend.unknown_venue') }}
                                            - {{ __('frontend.round') }} {{ $fixture->round->name ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
                                    <div
                                        style="width:50px;height:50px;border-radius:8px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-calendar" style="color:rgba(255,255,255,.6);"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">
                                            <span>
                                                {!! Helper::day_name($dt) !!}
                                                @if ($timeLabel)
                                                    • {{ $timeLabel }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if (!empty($fx['venue']['image']))
                                        <img src="{{ $fx['venue']['image'] }}" alt="Venue Image" class=""
                                            style="width:50px;height:50px;border-radius:8px;object-fit:cover;">
                                    @else
                                        <div
                                            style="width:50px;height:50px;border-radius:8px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-building" style="color:rgba(255,255,255,.6);"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $fx['venue']['name'] ?? __('frontend.unknown_venue') }}
                                        </div>
                                        <div class="text-muted" dir="{{ Helper::currentLanguage()->direction }}"
                                            style="font-size:14px;">
                                            <span
                                                dir="{{ Helper::currentLanguage()->direction }}">{{ $fx['venue']['city'] ?? '' }}</span>
                                            @if (!empty($fx['venue']['capacity']))
                                                • {{ number_format($fx['venue']['capacity']) }}
                                                {{ __('frontend.capacity') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- ممكن تضيف معلومات إضافية عن الملعب أو الفريقين إذا حابب --}}
                            </div>
                        </div>

                        @include('frontEnd.football.partials.probabilities') {{-- هذا ملف جديد خاص باحتمالات الفوز (أفضل من وضع الكود هنا مباشرة) --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('frontEnd.layouts.match-details') {{-- هذا ملف جديد فيه كود الجافا سكريبت الخاص بالتحديثات الحية (أفضل من وضعه هنا مباشرة) --}}
@endpush
