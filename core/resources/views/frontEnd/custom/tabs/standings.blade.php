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
                    $groupName =
                        data_get($g, 'group.name') ??
                        data_get($g, 'group_name') ??
                        data_get($g, 'name', '');

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

    <div class="standings-wrap">
        <div class="standings-head">
            <div class="standings-title">{{ $locale == 'ar' ? 'الترتيب' : 'Standings' }}</div>

            <div class="d-flex align-items-center gap-2">
                <div class="season-pill">
                    <span class="muted">{{ $locale == 'ar' ? 'الموسم' : 'Season' }}</span>
                    <strong>{{ __('frontend.current_season') }}</strong>
                </div>

                <div class="season-pill">
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
                $rows = $g['rows'] ?? collect();
            @endphp

            @if ($isGrouped && $groupTitle !== '')
                <div class="mb-2 mt-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <strong style="font-size: 14px;">{{ $groupTitle }}</strong>
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
                                // team
                                $team = data_get($row, 'participant');
                                $teamId = data_get($team, 'id');
                                $pos = (int) data_get($row, 'position', 0);
                                $pts = (int) data_get($row, 'points', 0);

                                // details map
                                $details = collect(data_get($row, 'details', []));
                                $codeMap = $details->mapWithKeys(function ($d) {
                                    $code = strtolower((string) data_get($d, 'type.code', ''));
                                    return [$code => data_get($d, 'value')];
                                });

                                // helpers
                                $gi = function (array $paths, $default = 0) use ($row) {
                                    foreach ($paths as $p) {
                                        $v = data_get($row, $p);
                                        if (is_numeric($v)) return (int) $v;
                                    }
                                    return (int) $default;
                                };

                                $gc = function (array $codes, $default = 0) use ($codeMap) {
                                    foreach ($codes as $c) {
                                        $v = $codeMap->get($c);
                                        if (is_numeric($v)) return (int) $v;
                                    }
                                    return (int) $default;
                                };

                                // ✅ played/w/d/l from multiple shapes
                                $played = $gi([
                                    'played','games_played','matches_played',
                                    'overall.played','overall.games_played','overall.matches_played',
                                    'stats.played','stats.games_played','stats.matches_played',
                                ], 0);

                                $won  = $gi(['won','wins','overall.won','overall.wins','stats.won','stats.wins'], 0);
                                $draw = $gi(['draw','draws','overall.draw','overall.draws','stats.draw','stats.draws'], 0);
                                $lost = $gi(['lost','losses','overall.lost','overall.losses','stats.lost','stats.losses'], 0);

                                // ✅ fallback from details codes
                                if ($won === 0)  $won  = $gc(['overall-won','won'], 0);
                                if ($draw === 0) $draw = $gc(['overall-draw','draw'], 0);
                                if ($lost === 0) $lost = $gc(['overall-lost','lost'], 0);

                                if ($played === 0) {
                                    $played = $gc([
                                        'overall-games-played','overall-matches-played','overall-played',
                                        'games-played','matches-played','played'
                                    ], 0);
                                }

                                if ($played === 0 && ($won + $draw + $lost) > 0) {
                                    $played = $won + $draw + $lost;
                                }

                                // ✅ GF/GA/GD
                                $gf = $gi(['goals_for','gf','overall.goals_for','overall.gf','stats.goals_for','stats.gf'], 0);
                                $ga = $gi(['goals_against','ga','overall.goals_against','overall.ga','stats.goals_against','stats.ga'], 0);

                                if ($gf === 0) $gf = $gc(['overall-goals-for','goals-for'], 0);
                                if ($ga === 0) $ga = $gc(['overall-goals-against','goals-against'], 0);

                                $gd = $gi(['goal_difference','gd','overall.goal_difference','overall.gd','stats.goal_difference','stats.gd'], $gf - $ga);
                                if ($gd === ($gf - $ga)) {
                                    $gd = $gc(['overall-goals-difference','goals-difference'], $gf - $ga);
                                }

                                // form last 5
                                $form = collect(data_get($row, 'form', []))
                                    ->sortBy('sort_order')
                                    ->pluck('form')
                                    ->filter()
                                    ->take(5)
                                    ->values();

                                // stripes (اختياري)
                                $stripe = 'stripe-none';
                                if ($pos >= 1 && $pos <= 4) $stripe = 'stripe-green';
                                elseif ($pos >= 5 && $pos <= 6) $stripe = 'stripe-orange';
                                elseif ($pos >= 18) $stripe = 'stripe-red';
                            @endphp

                            <tr class="{{ $stripe }}">
                                <td class="col-rank">
                                    <div class="club-cell">
                                        <div class="rank-box">
                                            <span class="rank-num">{{ $pos }}</span>
                                        </div>

                                        <div class="club-info">
                                            <img class="club-logo mx-2" src="{{ data_get($team, 'image_path', '') }}" alt="">

                                            @if ($teamId)
                                                <a href="{{ route('club.show', ['teamId' => $teamId]) }}" class="club-name">
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

@push('after-styles')
<style>
.form-dots{ display:flex; gap:6px; justify-content:flex-start; align-items:center; }
.form-dots .dot{
  width:18px;height:18px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:10px;font-weight:800;
  color:#fff;
  background:rgba(255,255,255,.18);
}
.form-dots .dot.W{ background:#2ecc71; }
.form-dots .dot.D{ background:#7f8c8d; }
.form-dots .dot.L{ background:#e74c3c; }
</style>
@endpush