{{-- STATS --}}
<div class="tab-pane fade show active" id="t-stats" role="tabpanel">
    <div class="card bg-dark text-light border-0 shadow-sm" style="border-radius:14px;">
        @php
            $status = $fx['status'] ?? 'NS';
            $p = $fx['probabilities'] ?? null;

            $pHome = (int) data_get($p, 'home', 0);
            $pDraw = (int) data_get($p, 'draw', 0);
            $pAway = (int) data_get($p, 'away', 0);
        @endphp
        @if($status === 'NS')
            <div id="js-ns-prob" style="display: {{ $status === 'NS' ? 'block' : 'none' }};">
                <div class="gx-ns-wrap">

                    <div class="gx-ns-title">{{ __('frontend.win_probability') }}</div>

                    @if ($p)
                        <div class="gx-ns-perc">
                            <div class="gx-ns-perc-item">
                                <div class="gx-ns-perc-val js-p-away">{{ $pAway }}%</div>
                                <div class="gx-ns-perc-label">{{ $fx['away']['name'] ?? 'الضيف' }}</div>
                            </div>

                            <div class="gx-ns-perc-item mid">
                                <div class="gx-ns-perc-val js-p-draw">{{ $pDraw }}%</div>
                                <div class="gx-ns-perc-label">{{ __('frontend.draw') }}</div>
                            </div>

                            <div class="gx-ns-perc-item">
                                <div class="gx-ns-perc-val js-p-home">{{ $pHome }}%</div>
                                <div class="gx-ns-perc-label">{{ $fx['home']['name'] ?? 'المضيف' }}</div>
                            </div>
                        </div>

                        <div class="gx-ns-bar">
                            <div class="gx-ns-bar-away js-bar-away" style="width: {{ $pAway }}%"></div>
                            <div class="gx-ns-bar-draw js-bar-draw" style="width: {{ $pDraw }}%"></div>
                            <div class="gx-ns-bar-home js-bar-home" style="width: {{ $pHome }}%"></div>
                        </div>
                    @else
                        <div class="text-muted text-center small">{{ __('frontend.no_win_probability') }}</div>
                    @endif

                </div>
            </div>
        @else
            <div class="card-body">
                @php
                    $stats = collect($fx['statistics_rows'] ?? []);
                @endphp

                <div class="gx-stats-head mb-3">
                    <img src="{{ $fx['home']['logo'] ?? '' }}" class="gx-team-ic" alt="">
                    <div class="gx-stats-title">{{ __('frontend.team_statistics') }}</div>
                    <img src="{{ $fx['away']['logo'] ?? '' }}" class="gx-team-ic" alt="">
                </div>

                @if ($stats->isEmpty())
                    <div class="text-muted">{{ __('frontend.no_team_statistics') }}</div>
                @else
                    <div class="gx-stats-list">
                        @foreach ($stats as $row)
                            <div class="gx-stat-row">
                                <div class="gx-val gx-left">
                                    <span class="gx-pill gx-pill-home">{{ $row['home'] ?? '-' }}</span>
                                </div>

                                <div class="gx-label">{{ $row['label'] ?? '-' }}</div>

                                <div class="gx-val gx-right">
                                    <span class="gx-pill gx-pill-away">{{ $row['away'] ?? '-' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
