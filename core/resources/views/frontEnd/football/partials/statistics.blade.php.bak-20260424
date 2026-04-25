{{-- STATS --}}
<div class="tab-pane fade show active" id="t-stats" role="tabpanel">
    <div class="card px-0 bg-dark text-light border-0" style="border-radius:14px;">
        @php
            $status = $fx['status'] ?? 'NS';
            $p = $fx['probabilities'] ?? null;

            $pHome = (int) data_get($p, 'home', 0);
            $pDraw = (int) data_get($p, 'draw', 0);
            $pAway = (int) data_get($p, 'away', 0);
        @endphp
        <div class="card-body">
            @php
                $stats = collect($fx['statistics_rows'] ?? []);
            @endphp

            <div class="gx-stats-head mb-3">
                <a href="{{route('team.details', ['id' => $fixture->homeTeam->id])}}">
                    <img src="{{ $fixture->homeTeam->image_path ?? '' }}" class="gx-team-ic" alt="">
                </a>
                <div class="gx-stats-title">{{ __('frontend.team_statistics') }}</div>
                <a href="{{route('team.details', ['id' => $fixture->awayTeam->id])}}">
                    <img src="{{ $fixture->awayTeam->image_path ?? '' }}" class="gx-team-ic" alt="">
                </a>
            </div>


            @if ($stats->isEmpty())
                <div class="text-muted">{{ __('frontend.no_team_statistics') }}</div>
            @else
                <div class="gx-stats-list">
                    @foreach ($stats as $row)
                        <div class="mb-2 card border-0">
                            <div class="gx-label text-center">{{ $row['label'] ?? '-' }}</div>
                            <div class="row">
                                @php($total_stats = $row['home'] + $row['away'])
                                <div class="col-6">
                                    <div class="fw-bold text-start gx-ns-perc-val js-p-home2">
                                        {{ $row['home'] }}
                                    </div>
                                    <div class="gx-ns-bar" dir="{{$locale == 'ar' ? 'ltr' : ''}}">
                                        <div class="gx-ns-bar-home2 js-bar-home2"
                                            style="width: {{ ($row['home'] / $total_stats) * 100 }}%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-end gx-ns-perc-val js-p-away2">
                                        {{ $row['away'] }}
                                    </div>
                                    <div class="gx-ns-bar">
                                        <div class="gx-ns-bar-away2 js-bar-away2"
                                            style="width: {{ ($row['away'] / $total_stats) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
