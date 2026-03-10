@extends('frontEnd.layouts.master')

@section('content')
    <div>
        <section id="content" style="margin-top: 200px">
            <div class="container">

                {{-- Filters --}}
                <div class="filters-card p-3 mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="fw-semibold mb-2">Bookmaker</div>
                            <form method="GET"
                                action="{{ route('round.odds', ['roundId' => $round['id'] ?? request()->route('roundId')]) }}">
                                <input type="hidden" name="market" value="{{ $selectedMarket }}">
                                <input type="hidden" name="lang" value="{{ $locale }}">
                                <select name="bookmaker" class="form-select" onchange="this.form.submit()">
                                    {{-- عدّل IDs حسب اللي عندك --}}
                                    <option value="2" @selected($selectedBookmaker == 2)>bet365</option>
                                    <option value="34" @selected($selectedBookmaker == 34)>Bookmaker #34</option>
                                </select>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <div class="fw-semibold mb-2">Market</div>
                            <form method="GET"
                                action="{{ route('round.odds', ['roundId' => $round['id'] ?? request()->route('roundId')]) }}">
                                <input type="hidden" name="bookmaker" value="{{ $selectedBookmaker }}">
                                <input type="hidden" name="lang" value="{{ $locale }}">
                                <select name="market" class="form-select" onchange="this.form.submit()">
                                    {{-- 1 = Fulltime Result (مثالك) --}}
                                    <option value="1" @selected($selectedMarket == 1)>Fulltime Result</option>
                                    <option value="2" @selected($selectedMarket == 2)>Market #2</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                @if (!empty($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif

                {{-- League / Round Header --}}
                <div class="league-bar mb-3">
                    <div class="p-3 d-flex align-items-center gap-2">
                        <div class="fw-semibold">
                            {{ data_get($league, 'country.name', '—') }}
                        </div>
                        <div class="text-muted">|</div>
                        <div>
                            {{ $league['name'] ?? '—' }}
                            @if (!empty($round['name']))
                                - {{ $round['name'] }}
                            @endif
                        </div>
                    </div>

                    <div class="px-3 pb-2 row-head d-flex justify-content-end gap-4">
                        <div style="width:240px" class="text-end">Home</div>
                        <div style="width:240px" class="text-end">Draw</div>
                        <div style="width:240px" class="text-end">Away</div>
                    </div>
                </div>

                {{-- Fixtures list --}}
                @forelse($fixtures as $fx)
                    @php
                        $participants = collect($fx['participants'] ?? []);
                        $home = $participants->firstWhere('meta.location', 'home');
                        $away = $participants->firstWhere('meta.location', 'away');

                        $start = data_get($fx, 'starting_at');
                        $time = $start ? \Carbon\Carbon::parse($start)->format('H:i') : '--:--';
                        $date = $start ? \Carbon\Carbon::parse($start)->format('d/m/y') : '';

                        // odds: نحولها لماب label => value (Home/Draw/Away)
                        // Odds entity فيها label و value و market_id و bookmaker_id. :contentReference[oaicite:2]{index=2}
                        $odds = collect(data_get($fx, 'odds.data', data_get($fx, 'odds', [])));

                        $oddsMap = $odds
                            ->filter(
                                fn($o) => (int) ($o['market_id'] ?? 0) === (int) $selectedMarket &&
                                    (int) ($o['bookmaker_id'] ?? 0) === (int) $selectedBookmaker,
                            )
                            ->mapWithKeys(fn($o) => [strtolower($o['label'] ?? '') => $o['value'] ?? null]);

                        $oddHome = $oddsMap->get('home');
                        $oddDraw = $oddsMap->get('draw');
                        $oddAway = $oddsMap->get('away');
                    @endphp

                    <div class="match-row mb-2">
                        <div class="row g-0 align-items-stretch">

                            {{-- Time / Date --}}
                            <div class="col-md-2 p-3">
                                <div class="time">{{ $time }}</div>
                                <div class="date">{{ $date }}</div>
                            </div>

                            {{-- Teams --}}
                            <div class="col-md-5 p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img class="team-logo" src="{{ $home['image_path'] ?? '' }}" alt="">
                                    <div class="fw-semibold">{{ $home['name'] ?? 'Home' }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <img class="team-logo" src="{{ $away['image_path'] ?? '' }}" alt="">
                                    <div class="fw-semibold">{{ $away['name'] ?? 'Away' }}</div>
                                </div>
                            </div>

                            {{-- Odds buttons --}}
                            <div class="col-md-5 p-3 d-flex justify-content-end gap-3 flex-wrap">
                                <button class="odds-btn py-2 px-3">{{ $oddHome ?? '—' }}</button>
                                <button class="odds-btn py-2 px-3">{{ $oddDraw ?? '—' }}</button>
                                <button class="odds-btn py-2 px-3">{{ $oddAway ?? '—' }}</button>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-muted">No fixtures found.</div>
                @endforelse
            </div>
        </section>

    </div>
@endsection
@push('after-styles')
    <style>

        .filters-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 14px;
        }

        .league-bar {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 14px;
            overflow: hidden;
        }

        .row-head {
            font-size: 12px;
            color: #667085;
        }

        .match-row {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 14px;
            overflow: hidden;
        }

        .odds-btn {
            min-width: 72px;
            border-radius: 10px;
            border: 1px solid #e6e7ee;
            background: #fff;
        }

        .team-logo {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .time {
            font-weight: 700;
        }

        .date {
            font-size: 12px;
            color: #667085;
        }
    </style>
@endpush
