@php
    $locale = $locale ?? 'ar';
    $pageItem = $paginatedPages->first();
    $pageTitle = $pageItem['title'] ?? '-';
    $fixtures = $pageItem['fixtures'] ?? collect();
    $pageType = $pageItem['type'] ?? 'round';
    $stage = $pageItem['stage'] ?? null;
    $round = $pageItem['round'] ?? null;
@endphp
<div class="gx-fixtures-grid">

    <div class="row">

        <div class="col-md-12">
            <div class="standings-head">
                <div class="standings-title">
                    {{ __('frontend.matches') }}
                    <h4 class="m-b-md">
                        {{ $pageTitle }}
                    </h4>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="season-pill">
                        <span class="muted">{{ __('frontend.season') }}</span>
                        <strong>{{ __('frontend.current_season') }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="matches matches-home">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($fixtures as $match)
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
                                            <a href="{{route('team.details', ['id' => $match->homeTeam->id])}}"
                                                class="d-flex flex-column align-items-center">
                                                @if ($match->homeTeam->image_path)
                                                    <div class="image d-flex align-items-center justify-content-center">
                                                        <img src="{{ $match->homeTeam->image_path }}" style="height:30px"
                                                            alt="">
                                                    </div>
                                                @endif
                                                <span class="mt-2 text-center">{{ $match->homeTeam->$name_var }}</span>
                                            </a>
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
                                            <a href="{{route('team.details', ['id' => $match->awayTeam->id])}}"
                                                class="d-flex flex-column align-items-center">
                                                @if ($match->awayTeam->image_path)
                                                    <div class="image d-flex align-items-center justify-content-center">
                                                        <img src="{{ $match->awayTeam->image_path }}" style="height:30px"
                                                            alt="">
                                                    </div>
                                                @endif
                                                <span class="mt-2 text-center">{{ $match->awayTeam->$name_var }}</span>
                                            </a>
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
            </div>
        </div>

        {{-- @endforeach --}}
    </div>

    <div class="row">
        <div class="col-lg-8">
            {!! $paginatedPages->appends(request()->query())->links() !!}
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
