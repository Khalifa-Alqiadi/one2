<div class="tab-pane fade" id="t-lineups" role="tabpanel">
    <div class="card bg-dark text-light border-0 shadow-sm gx-wrap">
        <div class="card-body">

            @php
                $lineups = collect($fx['lineups'] ?? []);

                $homeId = $fx['home']['id'] ?? null;
                $awayId = $fx['away']['id'] ?? null;

                // ✅ عندك team_id وليس participant_id
                $homeAll = $lineups->where('team_id', $homeId)->values();
                $awayAll = $lineups->where('team_id', $awayId)->values();

                // ✅ الأساسيين: type_id = 11
                $homeXI = $homeAll->where('type_id', 11)->values();
                $awayXI = $awayAll->where('type_id', 11)->values();

                // fallback لو ما اكتملت
                if ($homeXI->count() === 0) {
                    $homeXI = $homeAll->take(11);
                }
                if ($awayXI->count() === 0) {
                    $awayXI = $awayAll->take(11);
                }

                $homeBench = $homeAll->where('type_id', '!=', 11)->values();
                $awayBench = $awayAll->where('type_id', '!=', 11)->values();

                // helpers
                $pName = fn($p) => data_get($p, 'player.display_name') ??
                    (data_get($p, 'player.name') ?? (data_get($p, 'player_name') ?? '-'));

                $pNum = fn($p) => data_get($p, 'jersey_number') ?? '';

                $pImg = fn($p) => data_get($p, 'player.image_path') ?? '';

                // ✅ formation_field مثل "2:4"
                $parseField = function ($val) {
                    $val = (string) $val;
                    if (!str_contains($val, ':')) {
                        return [0, 0];
                    }
                    [$r, $c] = explode(':', $val, 2);
                    return [(int) $r, (int) $c];
                };

                // group by row index (r)
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

                $awayRows = $groupRows($awayXI);
                $homeRows = $groupRows($homeXI);

                // formation label من عدد الصفوف/اللاعبين (تقريبي)
                $formationLabel = function ($rows) {
                    // تجاهل صف الحارس غالبًا r=1
                    $rKeys = collect($rows->keys())->sort()->values();
                    $out = [];
                    foreach ($rKeys as $rk) {
                        $count = $rows[$rk]->count();
                        // افتراض: أقل صف فيه 1 هو الحارس
                        if ($count === 1) {
                            continue;
                        }
                        $out[] = $count;
                    }
                    return $out ? implode('-', $out) : '';
                };

                $homeFormation = $formationLabel($homeRows);
                $awayFormation = $formationLabel($awayRows);
            @endphp

            @if ($lineups->isEmpty())
                <div class="text-muted">لا توجد تشكيلات</div>
            @else
                {{-- عنوان علوي --}}
                <div class="gx-topline">
                    <div class="gx-formation">
                        {{ $awayFormation ?: '' }}
                    </div>
                    <div class="gx-team">
                        {{ $fixture->awayTeam->$name_var ?? '' }}
                    </div>
                </div>

                {{-- الملعب --}}
                <div class="gx-field">

                    {{-- away (فوق) --}}
                    @foreach ($awayRows as $rk => $row)
                        <div class="gx-line">
                            @foreach ($row->sortBy('_c') as $p)
                                @php
                                    $name = $pName($p);
                                    $num = $pNum($p);
                                    $img = $pImg($p);
                                    $initial = trim($name) ? mb_substr(trim($name), 0, 1) : '?';
                                @endphp

                                <div class="gx-player">
                                    <div class="gx-badge gx-away">
                                        <span class="gx-num">{{ $num }}</span>
                                    </div>
                                    <div class="gx-name">{{ $name }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <div class="gx-mid"></div>

                    {{-- home (تحت) --}}
                    @foreach ($homeRows as $rk => $row)
                        <div class="gx-line">
                            @foreach ($row->sortBy('_c') as $p)
                                @php
                                    $name = $pName($p);
                                    $num = $pNum($p);
                                    $img = $pImg($p);
                                    $initial = trim($name) ? mb_substr(trim($name), 0, 1) : '?';
                                @endphp

                                <div class="gx-player">
                                    <div class="gx-badge gx-home">
                                        <span class="gx-num">{{ $num }}</span>
                                    </div>
                                    <div class="gx-name">{{ $name }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- عنوان سفلي --}}
                <div class="gx-bottomline">
                    <div class="gx-team">
                        {{ $fixture->homeTeam->$name_var ?? '' }}
                    </div>
                    <div class="gx-formation">
                        {{ $homeFormation ?: '' }}
                    </div>
                </div>

                {{-- البدلاء --}}
                <div class="row g-3 mt-4">
                    <div class="col-lg-6">
                        <div class="fw-bold mb-2">{{ $fixture->homeTeam->$name_var ?? '' }} -
                            البدلاء</div>
                        <ul class="list-group list-group-flush">
                            @forelse($homeBench as $p)
                                <li
                                    class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between">
                                    <span>{{ $pName($p) }}</span>
                                    <span class="text-muted small">#{{ $pNum($p) }}</span>
                                </li>
                            @empty
                                <li class="list-group-item bg-dark text-muted border-secondary">
                                    لا يوجد
                                    بدلاء</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="col-lg-6">
                        <div class="fw-bold mb-2">{{ $fixture->awayTeam->$name_var ?? '' }} -
                            البدلاء</div>
                        <ul class="list-group list-group-flush">
                            @forelse($awayBench as $p)
                                <li
                                    class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between">
                                    <span>{{ $pName($p) }}</span>
                                    <span class="text-muted small">#{{ $pNum($p) }}</span>
                                </li>
                            @empty
                                <li class="list-group-item bg-dark text-muted border-secondary">
                                    لا يوجد
                                    بدلاء</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
