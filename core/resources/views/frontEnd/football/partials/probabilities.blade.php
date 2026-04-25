<div class="match-side-card" id="js-ns-prob" style="display: {{ ($fx['status'] ?? 'NS') === 'NS' ? 'block' : 'none' }};">
    @php
        $p = $fx['probabilities'] ?? null;
        $pHome = (int) data_get($p, 'home', 0);
        $pDraw = (int) data_get($p, 'draw', 0);
        $pAway = (int) data_get($p, 'away', 0);
    @endphp

    <div class="section-title-row">
        <h5>{{ __('frontend.win_probability') }}</h5>
        <span class="section-kicker">{{ __('frontend.statistics') }}</span>
    </div>

    @if ($p)
        <div class="d-flex flex-column gap-3">
            <div class="info-item d-block">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-bold">
                        <a href="{{ route('team.details', ['id' => $fixture->awayTeam->id]) }}">
                            {{ $fixture->awayTeam->$name_var ?? 'الضيف' }}
                        </a>
                    </div>
                    <div class="gx-ns-perc-val js-p-away">{{ $pAway }}%</div>
                </div>
                <div class="gx-ns-bar">
                    <div class="gx-ns-bar-away js-bar-away" style="width: {{ $pAway }}%"></div>
                </div>
            </div>

            <div class="info-item d-block">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-bold">{{ __('frontend.draw') }}</div>
                    <div class="gx-ns-perc-val js-p-draw">{{ $pDraw }}%</div>
                </div>
                <div class="gx-ns-bar">
                    <div class="gx-ns-bar-draw js-bar-draw" style="width: {{ $pDraw }}%"></div>
                </div>
            </div>

            <div class="info-item d-block">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-bold">
                        <a href="{{ route('team.details', ['id' => $fixture->homeTeam->id]) }}">
                            {{ $fixture->homeTeam->$name_var ?? 'المضيف' }}
                        </a>
                    </div>
                    <div class="gx-ns-perc-val js-p-home">{{ $pHome }}%</div>
                </div>
                <div class="gx-ns-bar">
                    <div class="gx-ns-bar-home js-bar-home" style="width: {{ $pHome }}%"></div>
                </div>
            </div>
        </div>
    @else
        <div class="soft-empty">{{ __('frontend.no_win_probability') }}</div>
    @endif
</div>
