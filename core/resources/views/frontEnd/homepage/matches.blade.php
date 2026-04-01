@php($matches = Helper::getMatchHome(8))
@php($name_var = 'name_' . @Helper::currentLanguage()->code)
@php($locale = @Helper::currentLanguage()->code)
@if (count($matches) > 0)
    <section class="matches matches-home py-5">
        <div class="container">
            <div class="section-title text-start mb-4">
                <h2 class="d-flex align-items-center gap-2">
                    <img src="{{ URL::to('uploads/settings/Vector.svg') }}" alt="">
                    {{ __('frontend.matches') }}
                </h2>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($matches as $match)
                    <?php
                        $isFinished = (bool) $match->is_finished;
                        $timezone = Helper::getUserTimezone();
                        $isTimeLive = false;
                        if (!$isFinished && $match->starting_at) {
                            try {
                                $start = \Carbon\Carbon::parse($match->starting_at);
                                $isTimeLive = now()->between(
                                    $start->copy()->subMinutes(15),
                                    $start->copy()->addHours(3),
                                );
                            } catch (\Throwable $e) {
                            }
                        }

                        $dt = $match->starting_at ? \Carbon\Carbon::parse($match->starting_at)->timezone($timezone) : null;
                        $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                        $timeLabel = $dt ? $dt->format('H:i') : '';

                        $minute = is_numeric($match->minute) ? (int) $match->minute : null;
                    ?>
                    <div class="col mb-3">
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
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @push('after-scripts')
        @include('frontEnd.layouts.match')
    @endpush
@endif
