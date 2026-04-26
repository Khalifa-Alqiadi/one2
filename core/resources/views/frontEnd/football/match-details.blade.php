@php
    $isRtl = ($locale ?? 'ar') === 'ar';
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp

@extends('frontEnd.layouts.master')

@section('content')
    @if ($fixture)
        @php
            $isFinished = (bool) $fixture->is_finished;
            $status = $fx['status'] ?? 'NS';
            $state_code = $fx['state_code'] ?? 'NS';
            $shouldLoadLiveScript =
                in_array(strtoupper((string) $status), ['LIVE'], true) ||
                in_array(strtoupper((string) $state_code), ['LIVE', 'INPLAY_1ST', 'INPLAY_2ND', 'HT'], true);
            $startAt = $fx['starting_at'] ?? null;
            $dt = $startAt ? \Carbon\Carbon::parse($startAt)->timezone(Helper::getUserTimezone()) : null;
            $timeLabel = $dt ? $dt->format('H:i') : '';
            $roundName = $fixture->round->name ?? '';
            $venueName = $fx['venue']['name'] ?? __('frontend.unknown_venue');
            $venueCity = $fx['venue']['city'] ?? '';
            $venueCapacity = !empty($fx['venue']['capacity']) ? number_format($fx['venue']['capacity']) : null;
            $stations = collect($fx['tv_stations'] ?? [])
                ->filter(fn($station) => is_array($station))
                ->unique(fn($station) => strtolower(trim($station['name'] ?? '')))
                ->values();
        @endphp

        <section id="content" class="football football-match details-match match-details-page">
            <div class="match-details-shell">
                <div class="container" style="direction: {{ $isRtl ? 'rtl' : 'ltr' }};">
                    <div class="row justify-content-center row-details g-4">
                        <div class="col-lg-8">
                            <div class="match-hero-top d-flex d-md-none">
                                <a href="{{ route('league.rounds', ['id' => $fixture->league->id]) }}" class="league-header bg-transparent p-0 border-0">
                                    @if (data_get($fixture->league, 'image_path'))
                                        <div class="logo">
                                            <img src="{{ data_get($fixture->league, 'image_path') }}" alt="">
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="mb-0 fw-bold">{{ data_get($fixture->league, $name_var, 'League') }}</h4>
                                        <span class="league-subtitle">
                                            {{ __('frontend.round') }} {{ $roundName ?: '-' }}
                                        </span>
                                    </div>
                                </a>

                                {{-- The date is shown above the score so the top area stays focused on the league. --}}
                            </div>
                            <div class="match-card match-hero">
                                <div class="match-hero-inner">
                                    <div class="match-hero-top d-none d-md-flex">
                                        <a href="{{ route('league.rounds', ['id' => $fixture->league->id]) }}"
                                            class="league-header">
                                            @if (data_get($fixture->league, 'image_path'))
                                                <div class="logo">
                                                    <img src="{{ data_get($fixture->league, 'image_path') }}"
                                                        alt="">
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="mb-0 fw-bold">
                                                    {{ data_get($fixture->league, $name_var, 'League') }}</h4>
                                                <span class="league-subtitle">
                                                    {{ __('frontend.round') }} {{ $roundName ?: '-' }}
                                                </span>
                                            </div>
                                        </a>

                                        {{-- The date is shown above the score so the top area stays focused on the league. --}}
                                    </div>

                                    <div class="match-clubs">
                                        <div class="club-box">
                                            <a href="{{ route('team.details', ['id' => $fixture->homeTeam->id]) }}">
                                                @if (!empty($fixture->homeTeam->image_path))
                                                    <img src="{{ $fixture->homeTeam->image_path }}" alt="">
                                                @else
                                                    <div class="club-logo-fallback">
                                                        {{ mb_substr($fixture->homeTeam->$name_var ?? 'H', 0, 1) }}
                                                    </div>
                                                @endif
                                                <div class="club-name">{{ $fixture->homeTeam->$name_var ?? '-' }}</div>
                                            </a>

                                            <div class="team-side-events text-start">
                                                @include('frontEnd.football.partials.top-events', [
                                                    'teamid' => $fixture->homeTeam->id,
                                                    'textDiration' => 'text-start',
                                                ])
                                            </div>
                                        </div>

                                        <div class="score-box-wrap">
                                            @if ($dt)
                                                <div class="score-date-label">
                                                    {!! Helper::day_name($dt, true) !!}
                                                </div>
                                            @endif

                                            <div class="score-frame js-scorebox"
                                                style="display: {{ $state_code !== 'NS' ? 'inline-flex' : 'none' }};">
                                                <span class="js-home fw-bold fs-1">{{ $fixture->home_score ?? '-' }}</span>
                                                <span class="score-separator">-</span>
                                                <span class="js-away fw-bold fs-1">{{ $fixture->away_score ?? '-' }}</span>
                                            </div>

                                            <div class="score-frame js-kickoffbox"
                                                style="display: {{ $state_code === 'NS' ? 'inline-flex' : 'none' }};">
                                                <span class="fw-bold">{{ __('frontend.not_started') }}</span>
                                            </div>

                                            <div class="match-status-stack">
                                                <span class="badge js-status"
                                                    style="display: {{ in_array($state_code, ['LIVE', 'INPLAY_2ND', 'INPLAY_1ST']) ? 'inline-block' : 'none' }};">
                                                    {{ __('frontend.live') }}
                                                </span>
                                                <span class="badge bg-light text-secondary js-ns"
                                                    style="display: {{ $state_code === 'NS' ? 'inline-block' : 'none' }};">
                                                    {{ __('frontend.not_started') }}
                                                </span>
                                                <span class="badge bg-light text-secondary js-ht"
                                                    style="display: {{ $state_code === 'HT' ? 'inline-block' : 'none' }};">
                                                    {{ __('frontend.half_time') }}
                                                </span>
                                                <span class="badge bg-light text-secondary js-ft"
                                                    style="display: {{ $state_code === 'FT' ? 'inline-block' : 'none' }};">
                                                    {{ __('frontend.finished') }}
                                                </span>
                                                <span class="badge bg-light text-secondary js-stop"
                                                    style="display: {{ $state_code === 'POSTP' ? 'inline-block' : 'none' }};">
                                                    {{ __('frontend.deferred') }}
                                                </span>
                                                <span class="fw-bold js-minute">
                                                    {{ $status === 'LIVE' && !empty($fx['minute']) ? $fx['minute'] . "'" : '' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="club-box">
                                            <a href="{{ route('team.details', ['id' => $fixture->awayTeam->id]) }}">
                                                @if (!empty($fixture->awayTeam->image_path))
                                                    <img src="{{ $fixture->awayTeam->image_path }}" alt="">
                                                @else
                                                    <div class="club-logo-fallback">
                                                        {{ mb_substr($fixture->awayTeam->$name_var ?? 'A', 0, 1) }}
                                                    </div>
                                                @endif
                                                <div class="club-name">{{ $fixture->awayTeam->$name_var ?? '-' }}</div>
                                            </a>

                                            <div class="team-side-events text-end">
                                                @include('frontEnd.football.partials.top-events', [
                                                    'teamid' => $fixture->awayTeam->id,
                                                    'textDiration' => 'text-end',
                                                ])
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="match-hero-foot">
                                        <div class="hero-stat">
                                            <div class="hero-stat-label">{{ __('frontend.round') }}</div>
                                            <div class="hero-stat-value">{{ $roundName ?: '-' }}</div>
                                        </div>
                                        <div class="hero-stat">
                                            <div class="hero-stat-label">{{ __('frontend.match_info') }}</div>
                                            <div class="hero-stat-value">
                                                {{ $venueCity ?: $venueName }}
                                            </div>
                                        </div>
                                        <div class="hero-stat">
                                            <div class="hero-stat-label">{{ __('frontend.capacity') }}</div>
                                            <div class="hero-stat-value">{{ $venueCapacity ?: '-' }}</div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>

                            <ul class="nav nav-tabs nav-fill my-4 match-tabs league-pills" role="tablist">
                                @if ($state_code !== 'NS')
                                    <li class="nav-item js-events-tab-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-events"
                                            type="button" role="tab">{{ __('frontend.events') }}</button>
                                    </li>
                                @endif
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-stats"
                                        type="button" role="tab">{{ __('frontend.statistics') }}</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-lineups" type="button"
                                        role="tab">{{ __('frontend.lineups') }}</button>
                                </li>
                                @if (count($standings) > 0)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-standings"
                                            type="button" role="tab">{{ __('frontend.standings') }}</button>
                                    </li>
                                @endif
                            </ul>

                            <div class="tab-content">
                                @include('frontEnd.football.partials.events')
                                @include('frontEnd.football.partials.statistics')
                                @include('frontEnd.football.partials.lineups')

                                @if (count($standings) > 0)
                                    @include('frontEnd.football.rounds-tabs.standings', [
                                        'standings' => $standings,
                                        'homeID' => $fixture->homeTeam->id,
                                        'awayID' => $fixture->awayTeam->id,
                                    ])
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="d-flex flex-column gap-4">
                                <div class="match-side-card">
                                    <div class="section-title-row">
                                        <h5>{{ __('frontend.match_info') }}</h5>
                                    </div>

                                    <div class="info-list">
                                        <a href="{{ route('league.rounds', ['id' => $fixture->league->id]) }}"
                                            class="info-item">
                                            <div class="info-icon">
                                                @if (!empty($fixture->league->image_path))
                                                    <img src="{{ $fixture->league->image_path }}" alt="">
                                                @else
                                                    <i class="fas fa-trophy"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ data_get($fixture->league, $name_var, 'League') }}
                                                </div>
                                                <div class="text-muted small">{{ __('frontend.round') }}
                                                    {{ $roundName ?: '-' }}</div>
                                            </div>
                                        </a>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{!! Helper::day_name($dt) !!}</div>
                                                @if ($timeLabel)
                                                    <div class="text-muted small">{{ $timeLabel }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                {{-- @if (!empty($fx['venue']['image']))
                                                    <img src="{{ $fx['venue']['image'] }}" alt="">
                                                @else --}}
                                                    <i class="fas fa-location-arrow"></i>
                                                {{-- @endif --}}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $venueName }}</div>
                                                <div class="text-muted small">
                                                    {{ $venueCity ?: '-' }}
                                                    @if ($venueCapacity)
                                                        <span>- {{ $venueCapacity }} {{ __('frontend.capacity') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @include('frontEnd.football.partials.probabilities')

                                {{-- @if (!$isFinished) --}}
                                    <div class="match-side-card">
                                        <div class="section-title-row">
                                            <h5>{{ __('frontend.tv_stations') }}</h5>
                                        </div>

                                        @if ($stations->isNotEmpty())
                                            <div class="tv-grid">
                                                @foreach ($stations as $station)
                                                    @if (!empty($station['image']))
                                                        <a href="{{ $station['url'] }}" target="_blank" nofollow rel="noopener"
                                                            class="tv-item-link">
                                                            <div class="tv-station-tile">
                                                                {{-- @if (!empty($station['image'])) --}}
                                                                    <img src="{{ $station['image'] }}" alt="">
                                                                {{-- @else
                                                                    <i class="fas fa-tv"></i>
                                                                @endif --}}
                                                                <span>{{ $station['name'] }}</span>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="soft-empty">{{ __('frontend.no_tv_stations') }}</div>
                                        @endif
                                    </div>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@push('after-scripts')
    @if (!empty($shouldLoadLiveScript))
        @include('frontEnd.layouts.match-details')
    @endif
@endpush
