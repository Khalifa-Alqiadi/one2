<div class="matches matches-home">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        @forelse ($matches as $match)
            @php
                $isFinished = (bool) $match->is_finished;
                $timezone = Helper::getUserTimezone();
                $isTimeLive = false;

                if (!$isFinished && $match->starting_at) {
                    try {
                        $start = \Carbon\Carbon::parse($match->starting_at);
                        $isTimeLive = now()->between(
                            $start->copy()->subMinutes(15),
                            $start->copy()->addHours(3)
                        );
                    } catch (\Throwable $e) {
                    }
                }

                $dt = $match->starting_at
                    ? \Carbon\Carbon::parse($match->starting_at)->timezone($timezone)
                    : null;

                $dateLabel = $dt ? $dt->translatedFormat('m/d') : '';
                $timeLabel = $dt ? $dt->format('H:i') : '';
                $minute = is_numeric($match->minute) ? (int) $match->minute : null;
            @endphp

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
        @empty
            <div class="col-12">
                <div class="text-center mb-0">
                    {{ __('frontend.no_matches_found') }}
                </div>
            </div>
        @endforelse
    </div>
</div>
