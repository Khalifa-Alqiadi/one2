@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp


@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football" style="margin-top: 200px">
        <div class="container">
            <div class="section-title text-start mb-4">
                <h2 class="d-flex align-items-center gap-2">
                    <img src="{{ URL::to('uploads/settings/live-icon-red1.svg') }}" alt="">
                    {{ __('frontend.live_matches') }}
                </h2>
            </div>
            @php
                $locale = Helper::currentLanguage()->code ?? 'ar';
            @endphp

            @if(count($liveMatches) === 0)
                <div class="text-white fs-5 py-5">
                    {{ __('frontend.no_live_matches') }}
                </div>
            @endif
            <div class="matches matches-home">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                    @foreach ($liveMatches as $match)
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
                        $timeLabel = $dt ? $dt->format('H:i') : '';

                        $minute = is_numeric($match->minute) ? (int) $match->minute : null;
                        ?>
                        @if($isTimeLive)
                            <div class="col mb-3">
                                @include('frontEnd.football.partials.match', [
                                    'match' => $match,
                                    'isFinished' => $isFinished,
                                    'isTimeLive' => $isTimeLive,
                                    'dateLabel' => $dateLabel,
                                    'timeLabel' => $timeLabel,
                                    'minute' => $minute,
                                ])
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('frontEnd.layouts.match')
@endpush
