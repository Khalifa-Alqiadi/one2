@php
    $leagues = Helper::isHomeLeagues();
    $name_var = 'name_' . @Helper::currentLanguage()->code;
    $locale = @Helper::currentLanguage()->code;
@endphp
@if (count($leagues) > 0)
    @foreach ($leagues as $league)
        <div class="home-leagues py-5">
            <div class="container">
                <div class="section-title d-flex justify-content-between align-items-center mb-0">
                    <h2 class="d-flex align-items-center gap-2">
                        <img src="{{ $league->image_path }}" alt="" style="width: 46px;">
                        {{ $league->$name_var }}
                    </h2>
                    <a href="{{ route('league.rounds', ['id' => $league->id]) }}" class="section-title-btn">
                        {{ __('frontend.viewMore') }}
                    </a>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @php
                        $matches = Helper::matchesLeague($league->id, 3);
                    @endphp
                    @if (count($matches) > 0)
                        @foreach ($matches as $match)
                            <?php
                            $isFinished = (bool) $match->is_finished;
                            $timezone = Helper::getUserTimezone();
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
                                <div class="card bg-transparent h-100 gx-fixture-card {{ $isTimeLive ? 'active' : '' }}"
                                    id="fixture-{{ $match->id }}" data-live="{{ $isTimeLive ? 1 : 0 }}">
                                    <div
                                        class="card-header d-flex align-items-center justify-content-between bg-transparent border-0 p-0 mb-3">

                                        <span class="js-minute">
                                            @if ($isTimeLive && $minute)
                                                {{ $minute }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="box-match row ">
                                        <div class="col-4">
                                            <div class="team d-flex flex-column align-items-center">
                                                @if ($match->homeTeam)
                                                    @if ($match->homeTeam->image_path)
                                                        <div
                                                            class="image d-flex align-items-center justify-content-center">
                                                            <img src="{{ $match->homeTeam->image_path }}"
                                                                style="height:30px" alt="">
                                                        </div>
                                                    @endif
                                                    <span
                                                        class="mt-2 text-center">{{ $match->homeTeam->$name_var }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4">
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
                                        </div>
                                        <div class="col-4">
                                            <div class="team d-flex flex-column align-items-center">
                                                @if ($match->awayTeam)
                                                    @if ($match->awayTeam->image_path)
                                                        <div
                                                            class="image d-flex align-items-center justify-content-center">
                                                            <img src="{{ $match->awayTeam->image_path }}"
                                                                style="height:30px" alt="">
                                                        </div>
                                                    @endif
                                                    <span
                                                        class="mt-2 text-center">{{ $match->awayTeam->$name_var }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="card-body border-top mt-3 pb-0 d-flex align-items-center justify-content-between">
                                        <span>
                                            {!! Helper::day_name($dt) !!}
                                            @if ($timeLabel)
                                                • {{ $timeLabel }}
                                            @endif
                                        </span>
                                        <a href="{{ route('match.show', ['id' => $match->id]) }}">
                                            {{ __('frontend.match_show') }}
                                            <i class="fas fa-arrow-{{ Helper::isRTL() ? 'left' : 'right' }} mx-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    @push('after-scripts')
        @include('frontEnd.layouts.match')
    @endpush
@endif
