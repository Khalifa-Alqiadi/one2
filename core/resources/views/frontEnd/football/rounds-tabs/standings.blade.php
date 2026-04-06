<div class="tab-pane fade" id="t-standings">

    @php
        $raw = collect($standings ?? []);

        $extractRows = function ($container) {
            $inner = data_get($container, 'standings', data_get($container, 'data', []));
            return is_array($inner) ? $inner : [];
        };

        $isGrouped = false;
        if ($raw->isNotEmpty() && is_array($raw->first())) {
            $first = $raw->first();
            $isGrouped =
                isset($first['standings']) ||
                isset($first['data']) ||
                data_get($first, 'group') ||
                data_get($first, 'group_id');
        }

        if ($isGrouped) {
            $groups = $raw
                ->map(function ($g) use ($extractRows) {
                    $groupName = data_get($g, 'group.name') ?? (data_get($g, 'group_name') ?? data_get($g, 'name', ''));

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

        if ($groups->isEmpty()) {
            $groups = collect([
                [
                    'group_name' => '',
                    'rows' => collect(),
                ],
            ]);
        }

        $updatedLabel = '';
        if (!empty($standingsUpdatedAt)) {
            try {
                $updatedLabel = \Carbon\Carbon::parse($standingsUpdatedAt)->diffForHumans();
            } catch (\Throwable $e) {
                $updatedLabel = (string) $standingsUpdatedAt;
            }
        }
    @endphp

    <div class="standings-wrap-v2">
        <div class="standings-head-v2">
            <div class="standings-title-v2">{{ $locale == 'ar' ? 'الترتيب' : 'Standings' }}</div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="season-pill-v2">
                    <span class="muted">{{ $locale == 'ar' ? 'آخر تحديث' : 'Last update' }}</span>
                    <strong>{{ $updatedLabel ?: ($locale == 'ar' ? 'غير متاح' : 'N/A') }}</strong>
                </div>

                <a class="btn btn-sm btn-outline-light"
                    href="{{ url()->current() . '?' . http_build_query(array_merge(request()->query(), ['refresh_standings' => 1])) }}">
                    {{ $locale == 'ar' ? 'تحديث' : 'Refresh' }}
                </a>
            </div>
        </div>

        @forelse($groups as $g)
            @php
                $groupTitle = trim($g['group_name'] ?? '');
                $rows = collect($g['rows'] ?? []);
            @endphp

            @if ($isGrouped && $groupTitle !== '')
                <div class="group-head-v2">
                    <strong>{{ $groupTitle }}</strong>
                    <span>{{ $locale == 'ar' ? 'عدد الفرق' : 'Teams' }}: {{ $rows->count() }}</span>
                </div>
            @endif

            <div class="table-responsive standings-table-responsive">
                <table class="table table-dark table-hover align-middle standings-table-v2 mb-0">
                    <thead>
                        <tr>
                            <th class="club-col text-start">{{ $locale == 'ar' ? 'النادي' : 'Club' }}</th>
                            <th class="text-center stat-col">{{ $locale == 'ar' ? 'لعب' : 'P' }}</th>
                            <th class="text-center stat-col">{{ $locale == 'ar' ? 'ف' : 'W' }}</th>
                            <th class="text-center stat-col">{{ $locale == 'ar' ? 'ت' : 'D' }}</th>
                            <th class="text-center stat-col">{{ $locale == 'ar' ? 'خ' : 'L' }}</th>
                            <th class="text-center stat-col">{{ $locale == 'ar' ? 'له' : 'GF' }}</th>
                            <th class="text-center stat-col">{{ $locale == 'ar' ? 'عليه' : 'GA' }}</th>
                            <th class="text-center stat-col">{{ $locale == 'ar' ? 'فرق' : 'GD' }}</th>
                            <th class="text-center stat-col pts-col">{{ $locale == 'ar' ? 'نقاط' : 'Pts' }}</th>
                            <th class="text-center form-col">{{ $locale == 'ar' ? 'آخر 5 مباريات' : 'Last 5' }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $team = data_get($row, 'participant', []);
                                $teamId = data_get($team, 'id');
                                $teamName = data_get($team, 'name', '-');
                                $teamLogo = data_get($team, 'image_path', '');
                                $pos = (int) data_get($row, 'position', 0);
                                $pts = (int) data_get($row, 'points', 0);

                                $details = collect(data_get($row, 'details', []));
                                $codeMap = $details->mapWithKeys(function ($d) {
                                    $code = strtolower(
                                        (string) (data_get($d, 'type.code') ??
                                            (data_get($d, 'type.developer_name') ?? (data_get($d, 'type.name') ?? ''))),
                                    );
                                    return [$code => data_get($d, 'value')];
                                });

                                $gi = function (array $paths, $default = 0) use ($row) {
                                    foreach ($paths as $p) {
                                        $v = data_get($row, $p);
                                        if (is_numeric($v)) {
                                            return (int) $v;
                                        }
                                    }
                                    return (int) $default;
                                };

                                $gc = function (array $codes, $default = 0) use ($codeMap) {
                                    foreach ($codes as $c) {
                                        $v = $codeMap->get(strtolower($c));
                                        if (is_numeric($v)) {
                                            return (int) $v;
                                        }
                                    }
                                    return (int) $default;
                                };

                                $played = $gi(
                                    [
                                        'played',
                                        'games_played',
                                        'matches_played',
                                        'overall.played',
                                        'overall.games_played',
                                        'overall.matches_played',
                                        'stats.played',
                                        'stats.games_played',
                                        'stats.matches_played',
                                    ],
                                    0,
                                );

                                $won = $gi(
                                    ['won', 'wins', 'overall.won', 'overall.wins', 'stats.won', 'stats.wins'],
                                    0,
                                );
                                $draw = $gi(
                                    [
                                        'draw',
                                        'draws',
                                        'drawn',
                                        'overall.draw',
                                        'overall.draws',
                                        'stats.draw',
                                        'stats.draws',
                                    ],
                                    0,
                                );
                                $lost = $gi(
                                    ['lost', 'losses', 'overall.lost', 'overall.losses', 'stats.lost', 'stats.losses'],
                                    0,
                                );

                                if ($played === 0) {
                                    $played = $gc(
                                        [
                                            'overall-games-played',
                                            'overall-matches-played',
                                            'overall-played',
                                            'games-played',
                                            'matches-played',
                                            'played',
                                        ],
                                        0,
                                    );
                                }

                                if ($won === 0) {
                                    $won = $gc(['overall-won', 'overall-wins', 'won', 'wins'], 0);
                                }
                                if ($draw === 0) {
                                    $draw = $gc(['overall-draw', 'overall-draws', 'draw', 'draws'], 0);
                                }
                                if ($lost === 0) {
                                    $lost = $gc(['overall-lost', 'overall-losses', 'lost', 'losses'], 0);
                                }

                                if ($played === 0 && $won + $draw + $lost > 0) {
                                    $played = $won + $draw + $lost;
                                }

                                $gf = $gi(
                                    [
                                        'goals_for',
                                        'gf',
                                        'overall.goals_for',
                                        'overall.gf',
                                        'stats.goals_for',
                                        'stats.gf',
                                    ],
                                    0,
                                );
                                $ga = $gi(
                                    [
                                        'goals_against',
                                        'ga',
                                        'overall.goals_against',
                                        'overall.ga',
                                        'stats.goals_against',
                                        'stats.ga',
                                    ],
                                    0,
                                );

                                if ($gf === 0) {
                                    $gf = $gc(['overall-goals-for', 'goals-for', 'goals_for', 'gf'], 0);
                                }
                                if ($ga === 0) {
                                    $ga = $gc(['overall-goals-against', 'goals-against', 'goals_against', 'ga'], 0);
                                }

                                $gd = $gi(
                                    [
                                        'goal_difference',
                                        'gd',
                                        'overall.goal_difference',
                                        'overall.gd',
                                        'stats.goal_difference',
                                        'stats.gd',
                                    ],
                                    $gf - $ga,
                                );

                                if ($gd === $gf - $ga) {
                                    $gd = $gc(
                                        ['overall-goals-difference', 'goals-difference', 'goal_difference', 'gd'],
                                        $gf - $ga,
                                    );
                                }

                                $form = collect(data_get($row, 'form', []))
                                    ->pluck('form')
                                    ->map(fn($f)=>strtoupper($f))
                                    ->take(5);

                                $rowClass = 'zone-none';
                                if ($pos >= 1 && $pos <= 4) {
                                    $rowClass = 'zone-green';
                                } elseif ($pos >= 5 && $pos <= 6) {
                                    $rowClass = 'zone-orange';
                                } elseif ($pos >= 18) {
                                    $rowClass = 'zone-red';
                                }
                            @endphp

                            <tr class="standing-row {{ $rowClass }} {{$teamId == $homeID ? 'active' : ''}}
                                {{$teamId == $awayID ? 'active' : ''}}">
                                <td class="club-col">
                                    <div class="club-cell-v2">
                                        <span class="rank-num-v2">{{ $pos }}</span>

                                        @if ($teamLogo)
                                            <img class="club-logo-v2" src="{{ $teamLogo }}"
                                                alt="{{ $teamName }}">
                                        @else
                                            <div class="club-logo-v2 club-logo-fallback-v2"></div>
                                        @endif

                                        @if ($teamId)
                                            <a href="{{ route('club.show', ['teamId' => $teamId]) }}"
                                                class="club-name-v2">
                                                {{ $teamName }}
                                            </a>
                                        @else
                                            <span class="club-name-v2">{{ $teamName }}</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="text-center stat-col">{{ $played }}</td>
                                <td class="text-center stat-col">{{ $won }}</td>
                                <td class="text-center stat-col">{{ $draw }}</td>
                                <td class="text-center stat-col">{{ $lost }}</td>
                                <td class="text-center stat-col">{{ $gf }}</td>
                                <td class="text-center stat-col">{{ $ga }}</td>
                                <td class="text-center stat-col">{{ $gd }}</td>
                                <td class="text-center stat-col pts-col-value">{{ $pts }}</td>

                                <td class="text-center form-col">
                                    <div class="form-dots-v2">
                                        @foreach ($form as $f)
                                            <span class="dot-v2 {{ $f }}">{{ $f }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    {{ $locale == 'ar' ? 'لا توجد بيانات' : 'No data' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                {{ $locale == 'ar' ? 'لا توجد بيانات ترتيب' : 'No standings data' }}
            </div>
        @endforelse
    </div>
</div>

@push('after-styles')
    <style>
        .standings-wrap-v2 {
            background: #121821;
            border: 1px solid rgba(255, 255, 255, .05);
            border-radius: 14px;
            overflow: hidden;
        }

        .standings-head-v2 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            padding: 14px 16px;
            background: #1a2130;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
        }

        .standings-title-v2 {
            color: #fff;
            font-size: 22px;
            font-weight: 800;
        }

        .season-pill-v2 {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .06);
            color: #fff;
            font-size: 13px;
        }

        .season-pill-v2 .muted {
            color: rgba(255, 255, 255, .65);
        }

        .group-head-v2 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 16px 6px;
            color: #fff;
        }

        .group-head-v2 span {
            color: rgba(255, 255, 255, .58);
            font-size: 12px;
        }

        .standings-table-responsive {
            padding: 0;
        }

        .standings-table-v2 {
            margin: 0;
            min-width: 1100px;
            --bs-table-bg: #121821;
            --bs-table-striped-bg: #121821;
            --bs-table-hover-bg: #18202b;
            --bs-table-border-color: rgba(255, 255, 255, .05);
            color: #fff;
        }

        .standings-table-v2 thead th {
            background: #121821;
            color: #dce6f5;
            font-size: 13px;
            font-weight: 700;
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, .06);
            white-space: nowrap;
        }

        .standings-table-v2 tbody td {
            padding: 14px 10px;
            white-space: nowrap;
            vertical-align: middle;
            font-weight: 600;
            color: #edf3fb;
        }

        .club-col {
            min-width: 240px;
        }

        .stat-col {
            min-width: 72px;
        }

        .form-col {
            min-width: 170px;
        }

        .club-cell-v2 {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            position: relative;
        }

        .rank-num-v2 {
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            min-width: 18px;
            text-align: center;
        }

        .club-logo-v2 {
            width: 22px;
            height: 22px;
            object-fit: contain;
            flex: 0 0 auto;
        }

        .club-logo-fallback-v2 {
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
        }

        .club-name-v2 {
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
        }

        .club-name-v2:hover {
            color: #fff;
            text-decoration: none;
            opacity: .92;
        }

        .pts-col {
            color: #9fd1ff !important;
        }

        .pts-col-value {
            color: #7ec3ff !important;
            font-size: 15px;
            font-weight: 800 !important;
        }

        .form-dots-v2 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .dot-v2 {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            color: #fff;
            background: #8a8f98;
        }

        .dot-v2.W {
            background: #2ecc71;
        }

        .dot-v2.D {
            background: #8d929a;
        }

        .dot-v2.L {
            background: #e85b48;
        }

        .standing-row td:first-child {
            position: relative;
        }

        .standing-row td:first-child::after {
            content: "";
            position: absolute;
            top: 6px;
            bottom: 6px;
            right: 0;
            width: 3px;
            border-radius: 4px;
            background: transparent;
        }

        .standing-row.zone-green td:first-child::after {
            background: #2ecc71;
        }

        .standing-row.zone-orange td:first-child::after {
            background: #f39c12;
        }

        .standing-row.zone-red td:first-child::after {
            background: #e74c3c;
        }

        @media (max-width: 768px) {
            .standings-title-v2 {
                font-size: 18px;
            }

            .club-col {
                min-width: 210px;
            }

            .stat-col {
                min-width: 60px;
            }

            .form-col {
                min-width: 150px;
            }
        }
    </style>
@endpush
