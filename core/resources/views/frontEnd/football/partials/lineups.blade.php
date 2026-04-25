<div class="tab-pane fade" id="t-lineups" role="tabpanel">
    <div class="match-tab-card">
        <div class="panel-card-body">
            @php
                $lineups = collect($fx['lineups'] ?? []);

                $homeId = $fx['home']['id'] ?? null;
                $awayId = $fx['away']['id'] ?? null;
                $homeAll = $lineups->where('team_id', $homeId)->values();
                $awayAll = $lineups->where('team_id', $awayId)->values();
                $homeXI = $homeAll->where('type_id', 11)->values();
                $awayXI = $awayAll->where('type_id', 11)->values();

                if ($homeXI->count() === 0) {
                    $homeXI = $homeAll->take(11);
                }
                if ($awayXI->count() === 0) {
                    $awayXI = $awayAll->take(11);
                }

                $homeBench = $homeAll->where('type_id', '!=', 11)->values();
                $awayBench = $awayAll->where('type_id', '!=', 11)->values();

                $pName = fn($p) => data_get($p, 'player.display_name') ?? (data_get($p, 'player.name') ?? (data_get($p, 'player_name') ?? '-'));
                $pNum = fn($p) => data_get($p, 'jersey_number') ?? '';
                $pImg = fn($p) => data_get($p, 'player.image_path') ?? '';

                $parseField = function ($val) {
                    $val = (string) $val;
                    if (!str_contains($val, ':')) {
                        return [0, 0];
                    }
                    [$r, $c] = explode(':', $val, 2);
                    return [(int) $r, (int) $c];
                };

                $groupRows = function ($players) use ($parseField) {
                    return $players
                        ->map(function ($p) use ($parseField) {
                            [$r, $c] = $parseField(data_get($p, 'formation_field'));
                            $p['_r'] = $r;
                            $p['_c'] = $c;
                            return $p;
                        })
                        ->sortBy(fn($p) => (int) ($p['_r'] ?? 0))
                        ->groupBy(fn($p) => (int) ($p['_r'] ?? 0));
                };

                $formationLabel = function ($rows) {
                    $rKeys = collect($rows->keys())->sort()->values();
                    $out = [];
                    foreach ($rKeys as $rk) {
                        $count = $rows[$rk]->count();
                        if ($count === 1) {
                            continue;
                        }
                        $out[] = $count;
                    }
                    return $out ? implode('-', $out) : '';
                };

                $awayRows = $groupRows($awayXI);
                $homeRows = $groupRows($homeXI);
                $homeFormation = $formationLabel($homeRows);
                $awayFormation = $formationLabel($awayRows);
            @endphp

            <div class="section-title-row">
                <h3>{{ __('frontend.lineups') }}</h3>
                <span class="section-kicker">{{ $homeFormation ?: '-' }} / {{ $awayFormation ?: '-' }}</span>
            </div>

            <div class="js-lineups-wrapper">
                @if ($lineups->isEmpty())
                    <div class="soft-empty">لا توجد تشكيلات</div>
                @else
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="info-item d-block">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="section-title-row mb-3">
                                            <h5>{{ $fixture->homeTeam->$name_var ?? '' }}</h5>
                                            <span class="section-kicker">{{ $homeFormation ?: '-' }}</span>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach ($homeXI as $p)
                                                <div class="lineup-roster-card d-flex align-items-center justify-content-between gap-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if ($pImg($p))
                                                            <img src="{{ $pImg($p) }}" alt="" style="width: 42px; height: 42px; border-radius: 12px; object-fit: cover;">
                                                        @else
                                                            <div class="info-icon" style="width: 42px; height: 42px; border-radius: 12px;">{{ mb_substr($pName($p), 0, 1) }}</div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-bold">{{ $pName($p) }}</div>
                                                            <div class="text-muted small">{{ data_get($p, 'position.name') ?? data_get($p, 'position.code') ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                    <span class="section-kicker">#{{ $pNum($p) ?: '-' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="section-title-row mb-3">
                                            <h5>{{ $fixture->awayTeam->$name_var ?? '' }}</h5>
                                            <span class="section-kicker">{{ $awayFormation ?: '-' }}</span>
                                        </div>
                                        <div class="d-flex flex-column gap-2">
                                            @foreach ($awayXI as $p)
                                                <div class="lineup-roster-card d-flex align-items-center justify-content-between gap-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if ($pImg($p))
                                                            <img src="{{ $pImg($p) }}" alt="" style="width: 42px; height: 42px; border-radius: 12px; object-fit: cover;">
                                                        @else
                                                            <div class="info-icon" style="width: 42px; height: 42px; border-radius: 12px;">{{ mb_substr($pName($p), 0, 1) }}</div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-bold">{{ $pName($p) }}</div>
                                                            <div class="text-muted small">{{ data_get($p, 'position.name') ?? data_get($p, 'position.code') ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                    <span class="section-kicker">#{{ $pNum($p) ?: '-' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item d-block">
                                <div class="section-title-row">
                                    <h5>{{ $fixture->homeTeam->$name_var ?? '' }} - البدلاء</h5>
                                    <span class="section-kicker">{{ $homeBench->count() }}</span>
                                </div>
                                @forelse ($homeBench as $p)
                                    <div class="bench-row">
                                        <span>{{ $pName($p) }}</span>
                                        <span class="text-muted small">#{{ $pNum($p) ?: '-' }}</span>
                                    </div>
                                @empty
                                    <div class="soft-empty">لا يوجد بدلاء</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item d-block">
                                <div class="section-title-row">
                                    <h5>{{ $fixture->awayTeam->$name_var ?? '' }} - البدلاء</h5>
                                    <span class="section-kicker">{{ $awayBench->count() }}</span>
                                </div>
                                @forelse ($awayBench as $p)
                                    <div class="bench-row">
                                        <span>{{ $pName($p) }}</span>
                                        <span class="text-muted small">#{{ $pNum($p) ?: '-' }}</span>
                                    </div>
                                @empty
                                    <div class="soft-empty">لا يوجد بدلاء</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
