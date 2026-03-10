@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" style="margin-top: 200px">
        <div class="container">
            {{-- <div class="d-flex gap-2 mb-3">
                <a class="btn btn-sm btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">EN</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}">AR</a>
            </div> --}}

            @if (!empty($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif

            @if(!empty($leagueName))
                <div class="mb-3 d-flex align-items-center gap-2">
                    <h4 class="mb-0 fw-bold d-flex align-items-center">                        
                        @if($leagueLogo)
                            <div class="logo p-2 rounded-circle bg-white">
                                <img src="{{ $leagueLogo }}" width="36" alt="{{ $leagueName }}">
                            </div>                    
                        @endif
                        <span class="mx-2">{{ $leagueName }}</span>
                    
                    </h4>
                </div>
            @endif


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
                        @forelse($rows as $row)
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

                                $gf = $map->get('goals for', $map->get('goals_scored', $map->get('goals scored', 0)));
                                $ga = $map->get('goals against', $map->get('goals_conceded', $map->get('goals conceded', 0)));
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
                                        <img class="team-logo" src="{{ data_get($team, 'image_path', '') }}" alt="">
                                        <span class="fw-semibold">{{ data_get($team, 'name', '-') }}</span>
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
                                        <span class="form-badge {{ $f }}">{{ $f }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted p-4">{{ __('frontend.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </section>
@endsection
@push('after-styles')
    <style>

        .table-wrap {
            border: 1px solid #6b7280;
            border-radius: 12px;
            overflow: hidden;
        }

        table {
            margin: 0;
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

        .team-logo {
            width: 20px;
            height: 20px;
            object-fit: contain;
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
