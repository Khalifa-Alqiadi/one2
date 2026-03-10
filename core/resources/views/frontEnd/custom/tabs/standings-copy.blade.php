<div class="tab-pane fade" id="t-standings">
    @if (!empty($standingsErr))
        <div class="alert alert-danger">{{ $standingsErr }}</div>
    @endif

    @php
        /**
         * SportMonks standings أشكال شائعة:
         * A) Rows مباشرة: [ { position, participant, details, form }, ... ]
         * B) Groups: [ { group:{...}, standings:[rows...] }, ... ]
         * C) Groups: [ { group:{...}, data:[rows...] }, ... ]
         */

        $raw = collect($standings ?? []);

        // helper: get rows from a container
        $extractRows = function ($container) {
            $inner = data_get($container, 'standings', data_get($container, 'data', []));
            return is_array($inner) ? $inner : [];
        };

        $isGrouped = false;

        // detect grouped format
        if ($raw->isNotEmpty() && is_array($raw->first())) {
            $first = $raw->first();
            $isGrouped =
                isset($first['standings']) ||
                isset($first['data']) ||
                data_get($first, 'group') ||
                data_get($first, 'group_id');
        }

        // Prepare groups
        if ($isGrouped) {
            $groups = $raw
                ->map(function ($g) use ($extractRows) {
                    $groupName =
                        data_get($g, 'group.name') ?? (data_get($g, 'group_name') ?? (data_get($g, 'name') ?? ''));

                    $rows = collect($extractRows($g))->values();

                    return [
                        'group_name' => $groupName,
                        'rows' => $rows,
                    ];
                })
                ->filter(fn($g) => $g['rows']->isNotEmpty())
                ->values();
        } else {
            $groups = collect([
                [
                    'group_name' => '',
                    'rows' => $raw,
                ],
            ]);
        }

        // fallback لو فاضي
        if ($groups->isEmpty()) {
            $groups = collect([
                [
                    'group_name' => '',
                    'rows' => collect(),
                ],
            ]);
        }
    @endphp

    <div class="standings-wrap">
        <div class="standings-head">
            <div class="standings-title">{{ $locale == 'ar' ? 'الترتيب' : 'Standings' }}</div>

            <div class="season-pill">
                <span class="muted">{{ $locale == 'ar' ? 'الموسم' : 'Season' }}</span>
                <strong>{{ $seasonId ?? '' }}</strong>
            </div>
        </div>

        @forelse($groups as $g)
            @php
                $groupTitle = trim($g['group_name'] ?? '');
                $rows = $g['rows'] ?? collect();
            @endphp

            @if ($isGrouped && $groupTitle !== '')
                <div class="mb-2 mt-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <strong style="font-size: 14px;">
                            {{ $groupTitle }}
                        </strong>
                        <span class="text-muted small">
                            {{ $locale == 'ar' ? 'عدد الفرق' : 'Teams' }}: {{ $rows->count() }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="standings-table-scroll">
                <table class="standings-table table table-dark table-hover">
                    <thead>
                        <tr>
                            <th class="col-rank">{{ $locale == 'ar' ? 'النادي' : 'Club' }}</th>
                            <th class="col-p text-center">{{ $locale == 'ar' ? 'لعب' : 'P' }}</th>
                            <th class="col-w text-center">{{ $locale == 'ar' ? 'ف' : 'W' }}</th>
                            <th class="col-d text-center">{{ $locale == 'ar' ? 'ت' : 'D' }}</th>
                            <th class="col-l text-center">{{ $locale == 'ar' ? 'خ' : 'L' }}</th>
                            <th class="col-gf text-center">{{ $locale == 'ar' ? 'له' : 'GF' }}</th>
                            <th class="col-ga text-center">{{ $locale == 'ar' ? 'عليه' : 'GA' }}</th>
                            <th class="col-gd text-center">{{ $locale == 'ar' ? 'فرق' : 'GD' }}</th>
                            <th class="col-pts text-center">{{ $locale == 'ar' ? 'نقاط' : 'Pts' }}</th>
                            <th class="col-form">{{ $locale == 'ar' ? 'آخر 5 مباريات' : 'Last 5' }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $team = data_get($row, 'participant');
                                $teamId = data_get($team, 'id');

                                $pos = (int) data_get($row, 'position', 0);
                                $pts = (int) data_get($row, 'points', 0);

                                $details = collect(data_get($row, 'details', []));

                                $codeMap = $details->mapWithKeys(function ($d) {
                                    $code = strtolower((string) data_get($d, 'type.code', ''));
                                    return [$code => (int) data_get($d, 'value', 0)];
                                });

                                // codes with fallbacks
                                $won = $codeMap->get('overall-won', $codeMap->get('won', 0));
                                $draw = $codeMap->get('overall-draw', $codeMap->get('draw', 0));
                                $lost = $codeMap->get('overall-lost', $codeMap->get('lost', 0));

                                $played =
                                    $codeMap->get('overall-games-played') ??
                                    ($codeMap->get('overall-matches-played') ??
                                        ($codeMap->get('overall-played') ??
                                            ($codeMap->get('games-played') ??
                                                ($codeMap->get('matches-played') ?? ($codeMap->get('played') ?? 0)))));

                                // ✅ fallback مضمون: لو played ما رجع من API
                                if ((int) $played === 0 && (int) $won + (int) $draw + (int) $lost > 0) {
                                    $played = (int) $won + (int) $draw + (int) $lost;
                                }
                                $gf = $codeMap->get('overall-goals-for', $codeMap->get('goals-for', 0));
                                $ga = $codeMap->get('overall-goals-against', $codeMap->get('goals-against', 0));
                                $gd = $codeMap->get(
                                    'overall-goals-difference',
                                    $codeMap->get('goals-difference', $gf - $ga),
                                );

                                $form = collect(data_get($row, 'form', []))
                                    ->sortBy('sort_order')
                                    ->pluck('form')
                                    ->filter()
                                    ->take(5)
                                    ->values();

                                // stripes (adjust as you want)
                                $stripe = 'stripe-none';
                                if ($pos >= 1 && $pos <= 4) {
                                    $stripe = 'stripe-green';
                                } elseif ($pos >= 5 && $pos <= 6) {
                                    $stripe = 'stripe-orange';
                                } elseif ($pos >= 18) {
                                    $stripe = 'stripe-red';
                                }
                            @endphp

                            <tr class="{{ $stripe }}">
                                <td class="col-rank">
                                    <div class="club-cell">
                                        <div class="rank-box">
                                            <span class="rank-num">{{ $pos }}</span>
                                        </div>

                                        <div class="club-info">
                                            <img class="club-logo mx-2" src="{{ data_get($team, 'image_path', '') }}"
                                                alt="">

                                            @if ($teamId)
                                                <a href="{{ route('club.show', ['teamId' => $teamId]) }}"
                                                    class="club-name">
                                                    {{ data_get($team, 'name', '-') }}
                                                </a>
                                            @else
                                                <span class="club-name">{{ data_get($team, 'name', '-') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center col-p">{{ $played }}</td>
                                <td class="text-center col-w">{{ $won }}</td>
                                <td class="text-center col-d">{{ $draw }}</td>
                                <td class="text-center col-l">{{ $lost }}</td>
                                <td class="text-center col-gf">{{ $gf }}</td>
                                <td class="text-center col-ga">{{ $ga }}</td>
                                <td class="text-center col-gd">{{ $gd }}</td>
                                <td class="text-center col-pts"><strong>{{ $pts }}</strong></td>

                                <td class="col-form">
                                    <div class="form-dots">
                                        @foreach ($form as $f)
                                            <span class="dot {{ $f }}">{{ $f }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty-row">
                                    {{ $locale == 'ar' ? 'لا توجد بيانات' : 'No data' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @empty
            <div class="text-muted">{{ $locale == 'ar' ? 'لا توجد بيانات ترتيب' : 'No standings data' }}</div>
        @endforelse
    </div>
</div>
