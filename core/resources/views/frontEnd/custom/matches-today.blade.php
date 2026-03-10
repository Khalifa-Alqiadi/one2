@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp
@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">

        <div class="container">

            @php
                $locale = $locale ?? 'ar';
                $tabs = [
                    'yesterday' => 'yesterday',
                    'today' => 'today',
                    'tomorrow' => 'tomorrow',
                ];

            @endphp

            <ul class="nav nav-pills league-pills mb-4 px-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? 'today') == 'yesterday' ? 'active' : '' }}"
                        href="{{ route('matches', ['tab' => 'yesterday']) }}">
                        {{ __('frontend.yesterdays_matches') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? 'today') == 'today' ? 'active' : '' }}"
                        href="{{ route('matches', ['tab' => 'today']) }}">
                        {{ __('frontend.todays_matches') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? 'today') == 'tomorrow' ? 'active' : '' }}"
                        href="{{ route('matches', ['tab' => 'tomorrow']) }}">
                        {{ $locale == 'ar' ? 'مباريات غدا' : 'Tomorrow' }}
                    </a>
                </li>
            </ul>
            <div class="tab-content cardx border-0 p-3 px-0">
                @foreach ($tabs as $tabKey => $tabLabel)
                    <div class="tab-pane fade {{ ($activeTab ?? 'today') == $tabKey ? 'show active' : '' }}"
                        id="{{ $tabKey }}" role="tabpanel">
                        <div class="gx-fixtures-grid">
                            <div class="row">
                                @foreach ($fixtures as $fx)
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

                                        $timezone = env('TIMEZONE', 'UTC'); // أو أي تايم زون تريده

                                        $dt = $fx->starting_at
                                            ? \Carbon\Carbon::parse($fx->starting_at)->timezone($timezone)
                                            : null;

                                        $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                                        $timeLabel = $dt ? $dt->format('H:i') : '';

                                        $homeScore = is_numeric($fx->home_score) ? (int) $fx->home_score : null;
                                        $awayScore = is_numeric($fx->away_score) ? (int) $fx->away_score : null;

                                        $minute = is_numeric($fx->minute) ? (int) $fx->minute : null;
                                    @endphp
                                    <div class="col-md-6">
                                        <a href="{{ route('match.show', ['id' => $fx->id]) }}" class="gx-fixture-card"
                                            id="fixture-{{ $fx->id }}" data-live="{{ $isTimeLive ? 1 : 0 }}">
                                            <p class="league">{{ $fx->league->$name_var ?? '' }}</p>
                                            <div class="match">
                                                <div class="gx-left">
                                                    <div class="gx-status">
                                                        <span class="js-live-badge">
                                                            @if ($isFinished)
                                                                النهائية
                                                            @elseif ($isTimeLive)
                                                                مباشر
                                                            @else
                                                                لم تبدأ
                                                            @endif
                                                        </span>

                                                        <span class="js-minute">
                                                            @if ($isTimeLive && $minute)
                                                                {{ $minute }}'
                                                            @endif
                                                        </span>

                                                        <span class="gx-datetime d-flex">
                                                            {!! Helper::day_name($dt) !!} @if ($timeLabel)
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
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    {!! $fixtures->appends(request()->query())->links() !!}
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('frontEnd.layouts.match')
@endpush
