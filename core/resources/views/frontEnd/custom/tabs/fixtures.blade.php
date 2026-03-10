@php
    $locale = $locale ?? 'ar';

@endphp

<div class="gx-fixtures-grid">
    {{-- <div class="standings-head">
        <div class="standings-title">{{ __('frontend.matches') }}</div>

        <div class="d-flex align-items-center gap-2">
            <div class="season-pill">
                <span class="muted">{{ $locale == 'ar' ? 'الموسم' : 'Season' }}</span>
                <strong>{{ __('frontend.current_season') }}</strong>
            </div>
        </div>
    </div> --}}
    <div class="row">

        @foreach ($rounds as $round)
            {{-- عنوان الجولة (اختياري) --}}
            {{-- <div class="gx-round-title">
                {{ $round->name ?? '' }}
            </div> --}}

            <div class="col-md-12">
                <div class="standings-head">
                    <div class="standings-title">
                        {{ __('frontend.matches') }}
                        <span
                            class="muted">{{ ' . ' .
                                __('frontend.matchday_progress', [
                                    'current' => $round->name,
                                    'total' => $roundsCount,
                                ]) }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <div class="season-pill">
                            <span class="muted">{{ $locale == 'ar' ? 'الموسم' : 'Season' }}</span>
                            <strong>{{ __('frontend.current_season') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($round->fixtures as $fx)
                @php
                    $isFinished = (bool) $fx->is_finished;

                    $isTimeLive = false;
                    if (!$isFinished && $fx->starting_at) {
                        try {
                            $start = \Carbon\Carbon::parse($fx->starting_at);
                            $isTimeLive = now()->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
                        } catch (\Throwable $e) {
                        }
                    }

                    $home = $fx->homeTeam;
                    $away = $fx->awayTeam;

                    $homeName = $locale == 'ar' ? $home->name_ar ?? $home->name_en : $home->name_en ?? $home->name_ar;
                    $awayName = $locale == 'ar' ? $away->name_ar ?? $away->name_en : $away->name_en ?? $away->name_ar;

                    $timezone = env('TIMEZONE', 'UTC');

                    $dt = $fx->starting_at ? \Carbon\Carbon::parse($fx->starting_at)->timezone($timezone) : null;
                    $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                    $timeLabel = $dt ? $dt->format('H:i') : '';

                    $homeScore = is_numeric($fx->home_score) ? (int) $fx->home_score : null;
                    $awayScore = is_numeric($fx->away_score) ? (int) $fx->away_score : null;

                    $minute = is_numeric($fx->minute) ? (int) $fx->minute : null;
                @endphp
                <div class="col-md-6">
                    <a href="{{ route('match.show', ['id' => $fx->id]) }}" class="gx-fixture-card mb-2"
                        id="fixture-{{ $fx->id }}" data-live="{{ $isTimeLive ? 1 : 0 }}">
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
                                <span class="js-home-score">{{ $homeScore !== null ? $homeScore : '' }}</span>
                                <span class="gx-dash">-</span>
                                <span class="js-away-score">{{ $awayScore !== null ? $awayScore : '' }}</span>
                            </div>

                            <div class="gx-teams">
                                <div class="gx-team-row">
                                    <img class="gx-team-logo" src="{{ $home->image_path ?? '' }}" alt="">
                                    <span class="gx-team-name">{{ $homeName }}</span>

                                </div>

                                <div class="gx-team-row">
                                    <img class="gx-team-logo" src="{{ $away->image_path ?? '' }}" alt="">
                                    <span class="gx-team-name">{{ $awayName }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8">
            {!! $rounds->appends(request()->query())->links() !!}
        </div>
    </div>

</div>

@push('after-styles')
    <style>

    </style>
@endpush

@push('after-scripts')
    @include('frontEnd.layouts.match')
@endpush
