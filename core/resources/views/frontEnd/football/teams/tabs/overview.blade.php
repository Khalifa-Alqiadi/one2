<div class="tab-pane fade show active" id="t-overview" role="tabpanel">
    <div class="matches matches-home">
        <div class="row">
            @php $i = 0; @endphp
            @forelse ($mainMatches as $match)
                @php
                    $i++;
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
                @endphp
                @if($i == 1)
                    <div class="col-md-12 mb-3">
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
                @if($i > 1)
                    <div class="col-md-6 mb-3">
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
            @empty
                <div class="col w-50">
                    <div class="text-center mb-0">
                        {{ __('frontend.no_matches_found') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
