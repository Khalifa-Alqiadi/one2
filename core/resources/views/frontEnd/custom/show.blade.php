@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" style="margin-top: 200px">

        <div class="container">

            {{-- Header --}}
            <div class="cardx p-3 mb-3 d-flex align-items-center gap-2 border-0">

                <h4 class="mb-0 fw-bold d-flex align-items-center">
                    @if (data_get($league, 'image_path'))
                        <div class="logo p-2 rounded-circle bg-white">
                            <img src="{{ data_get($league, 'image_path') }}" width="38" alt="">
                        </div>
                    @endif

                    <span class="mx-2">{{ data_get($league, 'name', 'League') }}</span>

                </h4>
            </div>

            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-4 px-0" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-fixtures"
                        type="button">
                        {{ $locale == 'ar' ? 'المباريات' : 'Fixtures' }}
                    </button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-players"
                        type="button">
                        {{ $locale == 'ar' ? 'اللاعبون' : 'Players' }}
                    </button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-standings"
                        type="button">
                        {{ $locale == 'ar' ? 'الترتيب' : 'Standings' }}
                    </button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-stats" type="button">
                        {{ $locale == 'ar' ? 'الإحصائيات' : 'Statistics' }}
                    </button></li>
            </ul>

            <div class="tab-content cardx border-0 p-3 px-0">
                {{-- Fixtures --}}
                <div class="tab-pane fade show active" id="t-fixtures">
                    @if ($fixturesErr)
                        <div class="alert alert-danger">{{ $fixturesErr }}</div>
                    @endif

                    @forelse($fixtures as $fx)
                        @php
                            $participants = collect($fx['participants'] ?? []);
                            $home = $participants->firstWhere('meta.location', 'home');
                            $away = $participants->firstWhere('meta.location', 'away');

                            $state = data_get($fx, 'state.name', '-');

                            $scoreArr = data_get($fx, 'scores.0.score', null);
                            $homeScore = is_array($scoreArr) ? $scoreArr['home'] ?? null : null;
                            $awayScore = is_array($scoreArr) ? $scoreArr['away'] ?? null : null;

                            $date = data_get($fx, 'starting_at', null);
                        @endphp

                        <div class="cardx p-2 mb-3 border-0 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <img class="team-logo" src="{{ $home['image_path'] ?? '' }}" alt="">
                                <strong>{{ $home['name'] ?? 'Home' }}</strong>
                                <span class="mx-1">vs</span>
                                <img class="team-logo" src="{{ $away['image_path'] ?? '' }}" alt="">
                                <strong>{{ $away['name'] ?? 'Away' }}</strong>
                            </div>
                            <div class="text-muted small mt-1">
                                {{ $locale == 'ar' ? 'الحالة' : 'State' }}: {{ $state }}
                                @if (!is_null($homeScore) && !is_null($awayScore))
                                    | {{ $locale == 'ar' ? 'النتيجة' : 'Score' }}: {{ $homeScore }} -
                                    {{ $awayScore }}
                                @endif
                                @if ($date)
                                    | {{ $locale == 'ar' ? 'التاريخ' : 'Date' }}: {{ $date }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">{{ $locale == 'ar' ? 'لا توجد مباريات' : 'No fixtures found' }}</div>
                    @endforelse
                </div>

                {{-- Players --}}
                <div class="tab-pane fade" id="t-players">
                    @if ($playersErr)
                        <div class="alert alert-danger">{{ $playersErr }}</div>
                    @endif

                    @forelse($playersBlocks as $block)
                        <div class="cardx mb-3">
                            <div class="p-2 border-bottom d-flex align-items-center gap-2">
                                <img class="team-logo" src="{{ $block['logo'] ?? '' }}" alt="">
                                <strong>{{ $block['team'] }}</strong>
                            </div>
                            <div class="p-2">
                                @php $squad = collect($block['squad'] ?? []); @endphp

                                @if ($squad->isEmpty())
                                    <div class="text-muted small">
                                        {{ $locale == 'ar' ? 'لا توجد بيانات لاعبين (قد تكون غير متاحة في اشتراكك)' : 'No squad data (may be unavailable in your plan)' }}
                                    </div>
                                @else
                                    <div class="row">
                                        @foreach ($squad->take(24) as $p)
                                            <div class="col-md-4 mb-2">
                                                <div class="border rounded p-2">
                                                    <strong>{{ data_get($p, 'player_name', data_get($p, 'player.name', '-')) }}</strong>
                                                    <div class="text-muted small">{{ data_get($p, 'position.name', '') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">{{ $locale == 'ar' ? 'لا توجد بيانات لاعبين' : 'No players data' }}</div>
                    @endforelse
                </div>

                {{-- Standings --}}
                <div class="tab-pane fade" id="t-standings">
                    @if ($standingsErr)
                        <div class="alert alert-danger">{{ $standingsErr }}</div>
                    @endif

                    {{-- بسيط: اعرض position + team + points (وسّعه كما تحب) --}}
                    <div class="table-responsive">
                        <div class="table-wrap">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:70px">{{ __('frontend.rank') }}</th>
                                        <th>{{ __('frontend.team') }}</th>
                                        <th class="text-center">{{ __('frontend.p') }}</th>
                                        <th class="text-center">{{ __('frontend.w') }}</th>
                                        <th class="text-center">{{ __('frontend.d') }}</th>
                                        <th class="text-center">{{ __('frontend.l') }}</th>
                                        <th class="text-center">{{ __('frontend.gf') }}</th>
                                        <th class="text-center">{{ __('frontend.ga') }}</th>
                                        <th class="text-center">{{ __('frontend.gd') }}</th>
                                        <th class="text-center">{{ __('frontend.pts') }}</th>
                                        <th class="text-center" style="width:160px">{{ __('frontend.form') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($standings as $row)
                                        @php
                                            // participant (فريق)
                                            $team = data_get($row, 'participant');

                                            // details: عادة ترجع array فيها type + value (played/won/draw/lost/goals for/against…)
                                            $details = collect(data_get($row, 'details', []));

                                            // حاول نقرأ القيم حسب type.name (لأن type_id يختلف بين الحسابات)
                                            $map = $details->mapWithKeys(function ($d) {
                                                $name = strtolower(data_get($d, 'type.name', ''));
                                                return [$name => (int) data_get($d, 'value', 0)];
                                            });

                                            // أسماء شائعة (قد تختلف حسب league) — إذا ما لقاها بيكون 0
                                            $played = $map->get('played', $map->get('games played', 0));
                                            $won = $map->get('won', $map->get('wins', 0));
                                            $draw = $map->get('draw', $map->get('draws', 0));
                                            $lost = $map->get('lost', $map->get('losses', 0));

                                            $gf = $map->get(
                                                'goals for',
                                                $map->get('goals_scored', $map->get('goals scored', 0)),
                                            );
                                            $ga = $map->get(
                                                'goals against',
                                                $map->get('goals_conceded', $map->get('goals conceded', 0)),
                                            );
                                            $gd = $map->get('goal difference', $gf - $ga);

                                            $pos = data_get($row, 'position', '');
                                            $pts = data_get($row, 'points', 0);

                                            // form: غالبًا array عناصر فيها form=W/D/L
                                            $form = collect(data_get($row, 'form', []))
                                                ->sortBy('sort_order')
                                                ->pluck('form')
                                                ->filter()
                                                ->take(5)
                                                ->values();
                                        @endphp

                                        <tr>
                                            <td>
                                                <span class="rank-bar"></span>
                                                <strong>{{ $pos }}</strong>
                                            </td>

                                            <td>
                                                <div class="team-cell">
                                                    <img class="team-logo" src="{{ data_get($team, 'image_path', '') }}"
                                                        alt="">
                                                    {{-- <span class="fw-semibold">{{ data_get($team, 'name', '-') }}</span> --}}
                                                    <a href="{{ route('club.show', $team['id']) }}"
                                                        class="fw-bold text-decoration-none">
                                                        {{ data_get($team, 'name', '-') }}
                                                    </a>
                                                </div>
                                            </td>

                                            <td class="text-center">{{ $played }}</td>
                                            <td class="text-center">{{ $won }}</td>
                                            <td class="text-center">{{ $draw }}</td>
                                            <td class="text-center">{{ $lost }}</td>
                                            <td class="text-center">{{ $gf }}</td>
                                            <td class="text-center">{{ $ga }}</td>
                                            <td class="text-center">{{ $gd }}</td>
                                            <td class="text-center pts">{{ $pts }}</td>

                                            <td class="text-center">
                                                @foreach ($form as $f)
                                                    <span
                                                        class="form-badge {{ $f }}">{{ $f }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted p-4">
                                                {{ __('frontend.no_data') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                        {{-- <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ $locale == 'ar' ? 'الفريق' : 'Team' }}</th>
                                    <th class="text-center">{{ $locale == 'ar' ? 'النقاط' : 'Pts' }}</th>
                                    <th class="text-center">{{ $locale == 'ar' ? 'آخر 5' : 'Form' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($standings as $row)
                                    @php
                                        $team = data_get($row, 'participant');
                                        $form = collect(data_get($row, 'form', []))
                                            ->sortBy('sort_order')
                                            ->pluck('form')
                                            ->filter()
                                            ->take(5)
                                            ->values();
                                    @endphp
                                    <tr>
                                        <td><strong>{{ data_get($row, 'position') }}</strong></td>
                                        <td class="d-flex align-items-center gap-2">
                                            <img class="team-logo" src="{{ data_get($team, 'image_path', '') }}"
                                                alt="">
                                            {{ data_get($team, 'name', '-') }}
                                        </td>
                                        <td class="text-center fw-bold">{{ data_get($row, 'points', 0) }}</td>
                                        <td class="text-center">
                                            @foreach ($form as $f)
                                                <span
                                                    class="badge {{ $f == 'W' ? 'bg-success' : ($f == 'D' ? 'bg-warning text-dark' : 'bg-danger') }}">{{ $f }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted">
                                            {{ $locale == 'ar' ? 'لا توجد بيانات ترتيب' : 'No standings data' }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table> --}}
                    </div>
                </div>

                {{-- Stats --}}
                <div class="tab-pane fade" id="t-stats">
                    <div class="text-muted">{{ $stats['note'] ?? '' }}</div>
                </div>
            </div>

        </div>



    </section>
@endsection
@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@push('after-styles')
    <style>
        body {
            background: #f6f7fb;
        }

        .cardx {
            border: 1px solid #eee;
        }

        .team-logo {
            width: 20px;
            height: 20px;
            object-fit: contain
        }

        .pill {
            border-radius: 999px;
            padding: .15rem .55rem;
            font-size: 12px;
            background: #f1f5f9
        }

        .nav-tabs li .nav-link {
            color: #fff;
        }

        .nav-tabs li .nav-link.active {
            background-color: #ecbb25;
        }

        thead th {
            font-size: 12px;
            color: #fff !important;
            background-color: #242424 !important;
            border-bottom: 1px solid #535457 !important;
        }

        tbody td {
            vertical-align: middle;
            color: #fff !important;
            background-color: #242424 !important;
            border-bottom: 1px solid #535457 !important;
        }

        .rank-bar {
            width: 3px;
            height: 26px;
            border-radius: 2px;
            display: inline-block;
            margin-inline-end: 10px;
            background: #22c55e;
        }

        .team-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-badge {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            margin-inline-start: 4px;
        }

        .W {
            background: #22c55e;
        }

        /* Win */
        .D {
            background: #f59e0b;
        }

        /* Draw */
        .L {
            background: #ef4444;
        }

        /* Loss */
        .pts {
            font-weight: 700;
        }
    </style>
@endpush
