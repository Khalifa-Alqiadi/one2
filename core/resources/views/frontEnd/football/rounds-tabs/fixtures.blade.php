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

        {{-- @foreach ($rounds as $round) --}}
        {{-- عنوان الجولة (اختياري) --}}
        {{-- <div class="gx-round-title">
                {{ $round->name ?? '' }}
            </div> --}}

        <div class="col-md-12">
            <div class="standings-head">
                <div class="standings-title">
                    {{ __('frontend.matches') }}
                    {{-- <span
                            class="muted">{{ ' . ' .
                                __('frontend.matchday_progress', [
                                    'current' => $round->name,
                                    'total' => $roundsCount,
                                ]) }}</span> --}}
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
                        @include('frontEnd.football.partials.match', [
                            'match' => $match,
                            'isFinished' => $isFinished,
                            'isTimeLive' => $isTimeLive,
                            'dateLabel' => $dateLabel,
                            'timeLabel' => $timeLabel,
                            'minute' => $minute,
                        ])
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
