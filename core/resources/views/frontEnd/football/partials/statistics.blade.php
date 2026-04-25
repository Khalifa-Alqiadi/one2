<div class="tab-pane fade show active" id="t-stats" role="tabpanel">
    @php
        $stats = collect($fx['statistics_rows'] ?? []);
    @endphp

    <div class="match-tab-card js-stats-section">
        <div class="panel-card-body">
            <div class="section-title-row">
                <h3>{{ __('frontend.statistics') }}</h3>
                <span class="section-kicker">{{ __('frontend.team_statistics') }}</span>
            </div>

            <div class="gx-stats-head mb-4">
                <a href="{{ route('team.details', ['id' => $fixture->homeTeam->id]) }}">
                    <img src="{{ $fixture->homeTeam->image_path ?? '' }}" class="gx-team-ic" alt="">
                </a>
                <div class="gx-stats-title">{{ __('frontend.team_statistics') }}</div>
                <a href="{{ route('team.details', ['id' => $fixture->awayTeam->id]) }}">
                    <img src="{{ $fixture->awayTeam->image_path ?? '' }}" class="gx-team-ic" alt="">
                </a>
            </div>

            <div class="js-stats-wrapper">
                @if ($stats->isEmpty())
                    <div class="soft-empty">{{ __('frontend.no_team_statistics') }}</div>
                @else
                    <div class="d-flex flex-column gap-3 gx-stats-list">
                        @foreach ($stats as $row)
                            @php
                                $homeValue = (float) ($row['home'] ?? 0);
                                $awayValue = (float) ($row['away'] ?? 0);
                                $totalStats = $homeValue + $awayValue;
                                $homePercent = $totalStats > 0 ? ($homeValue / $totalStats) * 100 : 0;
                                $awayPercent = $totalStats > 0 ? ($awayValue / $totalStats) * 100 : 0;
                            @endphp

                            <div class="info-item d-block">
                                <div class="gx-label text-center mb-3 fw-bold">{{ $row['label'] ?? '-' }}</div>
                                <div class="row align-items-center g-3">
                                    <div class="col-6">
                                        <div class="fw-bold text-start gx-ns-perc-val js-p-home2">{{ $row['home'] ?? 0 }}</div>
                                        <div class="gx-ns-bar" dir="{{ $locale == 'ar' ? 'ltr' : '' }}">
                                            <div class="gx-ns-bar-home2 js-bar-home2" style="width: {{ $homePercent }}%"></div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="fw-bold text-end gx-ns-perc-val js-p-away2">{{ $row['away'] ?? 0 }}</div>
                                        <div class="gx-ns-bar">
                                            <div class="gx-ns-bar-away2 js-bar-away2" style="width: {{ $awayPercent }}%"></div>
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
</div>
