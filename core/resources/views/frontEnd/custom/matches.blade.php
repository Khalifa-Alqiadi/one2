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
                        $timezone = Helper::getUserTimezone();
                        $isTimeLive = false;
                        if (!$isFinished && $match->starting_at) {
                            try {
                                $start = \Carbon\Carbon::parse($match->starting_at);
                                $isTimeLive = now()
                                    ->between($start->copy()->subMinutes(15), $start->copy()->addHours(3));
                            } catch (\Throwable $e) {
                            }
                        }

                        $dt = $match->starting_at ? \Carbon\Carbon::parse($match->starting_at)->timezone($timezone) : null;
                        $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                        $timeLabel = $dt ? $dt->format('h:i A') : '';

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
                                            <div class="d-flex gx-score align-items-center justify-content-center gap-2">
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
                                    <span>{!! Helper::day_name($dt) !!}
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
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('frontEnd.layouts.match')
@endpush
