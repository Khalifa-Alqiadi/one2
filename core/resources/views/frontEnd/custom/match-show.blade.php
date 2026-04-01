@php
    $isRtl = ($locale ?? 'ar') === 'ar';
    $name_var = 'name_' . @Helper::currentLanguage()->code;

@endphp
@extends('frontEnd.layouts.master')

@section('content')
    <section id="content" class="football football-match" style="margin-top: 100px">
        <div class="container my-4" style="direction: {{ $isRtl ? 'rtl' : 'ltr' }};">

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <a href="{{route('league.show', ['id' => $fixture->league->id])}}"  class="league-header mb-3">
                        @if (data_get($fixture->league, 'image_path'))
                            <div class="logo rounded-circle bg-white">
                                <img src="{{ data_get($fixture->league, 'image_path') }}" alt="">
                            </div>
                        @endif
                        <h4 class="mb-0 fw-bold">{{ data_get($fixture->league, $name_var, 'League') }}</h4>
                    </a>
                    @if ($err)
                        <div class="alert alert-danger">{{ $err }}</div>
                    @endif

                    @if (!$fx)
                        <div class="text-muted">{{ __('frontend.no_data') }}</div>
                    @else
                        {{-- Header Card --}}
                        <div class="card bg-dark text-light border-0 shadow-sm mb-3" style="border-radius:14px;">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    {{-- Home --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $fixture->homeTeam->image_path ?? '' }}"
                                            style="width:34px;height:34px;border-radius:50%;object-fit:contain;background:rgba(255,255,255,.08);padding:4px;">
                                        <strong>{{ $fixture->homeTeam->$name_var ?? '-' }}</strong>
                                    </div>

                                    {{-- Score --}}
                                    @php
                                        $status = $fx['status'] ?? 'NS';
                                        $state_code = $fx['state_code'] ?? 'NS';
                                        $startAt = $fx['starting_at'] ?? null;
                                    @endphp

                                    {{-- Score / Kickoff --}}
                                    <div class="text-center">

                                        {{-- ✅ Box: Score (LIVE/HT/FT) --}}
                                        <div class="js-scorebox"
                                            style="font-size:28px;font-weight:800;letter-spacing:1px; display: {{ $state_code === 'NS' ? 'none' : 'block' }};">
                                            <span class="js-home">{{ $fx['score']['home'] ?? '-' }}</span>
                                            <span style="opacity:.6;margin:0 10px;">-</span>
                                            <span class="js-away">{{ $fx['score']['away'] ?? '-' }}</span>
                                        </div>

                                        {{-- ✅ Box: Not started (NS) --}}
                                        <div class="js-kickoffbox">
                                            <div class="fw-bold" style="font-size:14px; opacity:.95;
                                                display: {{ $state_code === 'NS' ? 'block' : 'none' }}">لم تبدأ بعد</div>
                                            <div class="text-muted small js-kickoff">
                                                {{ $startAt ? \Carbon\Carbon::parse($startAt)->timezone(Helper::getUserTimezone())->format('H:i  -  Y/m/d') : '' }}
                                            </div>
                                        </div>

                                        <div class="small mt-2">
                                            <span class="badge bg-success js-status"
                                                style="display: {{ $state_code === 'LIVE' ? 'inline-block' : 'none' }};">مباشر</span>

                                            <span class="badge bg-secondary js-ns"
                                                style="display: {{ $status === 'NS' ? 'inline-block' : 'none' }};">لم
                                                تبدأ</span>

                                            <span class="badge bg-secondary js-ht"
                                                style="display: {{ $status === 'HT' ? 'inline-block' : 'none' }};">منتصف
                                                المباراة</span>

                                            <span class="badge bg-secondary js-ft"
                                                style="display: {{ $status === 'FT' ? 'inline-block' : 'none' }};">النهائية</span>

                                            <span class="text-success fw-bold js-minute">
                                                {{ $status === 'LIVE' && !empty($fx['minute']) ? $fx['minute'] . "'" : '' }}
                                            </span>

                                        </div>
                                        <div class="text-muted small ">
                                            {{ $startAt ? \Carbon\Carbon::parse($startAt)->timezone(Helper::getUserTimezone())->format('H:i  -  Y/m/d') : '' }}
                                        </div>
                                    </div>

                                    {{-- Away --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <strong>{{ $fixture->awayTeam->$name_var ?? '-' }}</strong>
                                        <img src="{{ $fixture->awayTeam->image_path ?? '' }}"
                                            style="width:34px;height:34px;border-radius:50%;object-fit:contain;background:rgba(255,255,255,.08);padding:4px;">
                                    </div>
                                </div>
                            </div>

                        </div>
                        @php
                            $status = $fx['status'] ?? 'NS';
                        @endphp
                        {{-- Tabs --}}
                        <ul class="nav nav-tabs nav-fill mb-3 league-pills" role="tablist"
                            style="border-color: rgba(255,255,255,.08);">
                            @if ($status !== 'NS')
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#t-events" type="button"
                                        role="tab">{{ __('frontend.events') }}</button>
                                </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#t-stats"
                                    type="button" role="tab">{{ __('frontend.statistics') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " data-bs-toggle="tab" data-bs-target="#t-lineups" type="button"
                                    role="tab">{{ __('frontend.lineups') }}</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- EVENTS --}}
                            @if ($status !== 'NS')
                                <div class="tab-pane fade " id="t-events" role="tabpanel">
                                    <div class="card bg-dark text-light border-0 shadow-sm" style="border-radius:14px;">
                                        <div class="card-body">
                                            @php
                                                // events ممكن تجي مباشرة أو داخل data
                                                $events = $fx['events'] ?? [];
                                                if (
                                                    is_array($events) &&
                                                    isset($events['data']) &&
                                                    is_array($events['data'])
                                                ) {
                                                    $events = $events['data'];
                                                }
                                                $timeline = collect($events)->values();
                                            @endphp

                                            <div class="gx-timeline">

                                                @forelse($timeline as $e)
                                                    @php
                                                        $kind = $e['kind'] ?? 'other'; // sub | goal | other
                                                        $minute = $e['minute_label'] ?? '';

                                                        // صور/لقطات (اختياري)
                                                        $media = (array) ($e['media'] ?? []); // ['main'=>'', 'a'=>'', 'b'=>'']

                                                        // SUB
                                                        $sub = $e['sub'] ?? [];
                                                        $in = $sub['in'] ?? [];
                                                        $out = $sub['out'] ?? [];

                                                        $inName = (string) ($in['name'] ?? '');
                                                        $outName = (string) ($out['name'] ?? '');

                                                        $inImg = (string) ($in['image'] ?? '');
                                                        $outImg = (string) ($out['image'] ?? '');

                                                        $inNum = $in['number'] ?? '';
                                                        $outNum = $out['number'] ?? '';

                                                        $inPos = (string) ($in['pos'] ?? '');
                                                        $outPos = (string) ($out['pos'] ?? '');

                                                        $inInitial = trim($inName)
                                                            ? mb_substr(trim($inName), 0, 1)
                                                            : '?';
                                                        $outInitial = trim($outName)
                                                            ? mb_substr(trim($outName), 0, 1)
                                                            : '?';

                                                        // GOAL (اختياري)
                                                        $goal = $e['goal'] ?? [];
                                                        $scorerName =
                                                            (string) ($goal['scorer_name'] ??
                                                                ($e['player_name'] ?? ''));
                                                        $scorerImg = (string) ($goal['scorer_image'] ?? '');
                                                        $assistName = (string) ($goal['assist_name'] ?? '');
                                                        $scoreLine = (string) ($goal['scoreline'] ?? ''); // مثال: "برشلونة 3 - 0 ..."
                                                    @endphp

                                                    {{-- ===== صور الحدث (مثل الصورة) ===== --}}
                                                    @if (!empty($media))
                                                        <div class="gx-event-media">
                                                            <div class="gx-media-grid">
                                                                @if (!empty($media['main']))
                                                                    <div class="gx-media-main">
                                                                        <img src="{{ $media['main'] }}" alt=""
                                                                            loading="lazy" onerror="this.remove();">
                                                                    </div>
                                                                @endif

                                                                <div class="gx-media-thumbs">
                                                                    @if (!empty($media['a']))
                                                                        <div class="gx-media-thumb">
                                                                            <img src="{{ $media['a'] }}" alt=""
                                                                                loading="lazy" onerror="this.remove();">
                                                                        </div>
                                                                    @endif
                                                                    @if (!empty($media['b']))
                                                                        <div class="gx-media-thumb">
                                                                            <img src="{{ $media['b'] }}" alt=""
                                                                                loading="lazy" onerror="this.remove();">
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- ===== SUB CARD ===== --}}
                                                    @if ($kind === 'sub')
                                                        <div class="gx-card gx-sub-card">
                                                            <div class="gx-card-head">
                                                                <div class="gx-minute">{{ $minute }}</div>

                                                                <div class="gx-title">
                                                                    <span class="gx-icon">🔁</span>
                                                                    تبديل لاعب
                                                                </div>
                                                            </div>

                                                            <div class="gx-card-body">
                                                                {{-- IN --}}
                                                                <div class="gx-row">
                                                                    <div class="gx-tag gx-in">دخل</div>

                                                                    <div class="gx-player">
                                                                        <div class="gx-name">{{ $inName ?: '-' }}</div>
                                                                        <div class="gx-meta">
                                                                            @if ($inPos)
                                                                                <span>{{ $inPos }}</span>
                                                                            @endif
                                                                            @if ($inNum)
                                                                                <span>• #{{ $inNum }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="gx-avatar gx-in-ring"
                                                                        title="{{ $inName }}">
                                                                        @if ($inImg)
                                                                            <img src="{{ $inImg }}" alt=""
                                                                                loading="lazy" onerror="this.remove();">
                                                                        @endif
                                                                        <div class="gx-fallback">{{ $inInitial }}</div>
                                                                    </div>
                                                                </div>

                                                                {{-- OUT --}}
                                                                <div class="gx-row mt">
                                                                    <div class="gx-tag gx-out">خرج</div>

                                                                    <div class="gx-player">
                                                                        <div class="gx-name">{{ $outName ?: '-' }}</div>
                                                                        <div class="gx-meta">
                                                                            @if ($outPos)
                                                                                <span>{{ $outPos }}</span>
                                                                            @endif
                                                                            @if ($outNum)
                                                                                <span>• #{{ $outNum }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="gx-avatar gx-out-ring"
                                                                        title="{{ $outName }}">
                                                                        @if ($outImg)
                                                                            <img src="{{ $outImg }}" alt=""
                                                                                loading="lazy" onerror="this.remove();">
                                                                        @endif
                                                                        <div class="gx-fallback">{{ $outInitial }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- ===== GOAL CARD (مثل البنفسجي بالصورة) ===== --}}
                                                    @if ($kind === 'goal')
                                                        <div class="gx-card gx-goal-card">
                                                            <div class="gx-goal-top">
                                                                <div class="gx-goal-chip">
                                                                    <span class="gx-goal-icon">⚽</span>
                                                                    هدف
                                                                    <div class="gx-goal-minute">{{ $minute }}</div>
                                                                </div>
                                                            </div>

                                                            <div class="gx-goal-scoreline">
                                                                {{ $scoreLine ?: '' }}
                                                            </div>

                                                            <div class="gx-goal-body">
                                                                <div class="gx-goal-player">
                                                                    <div class="gx-avatar gx-goal-ring">
                                                                        @if ($scorerImg)
                                                                            <img src="{{ $scorerImg }}" alt=""
                                                                                loading="lazy" onerror="this.remove();">
                                                                        @endif
                                                                        <div class="gx-fallback">
                                                                            {{ trim($scorerName) ? mb_substr(trim($scorerName), 0, 1) : '?' }}
                                                                        </div>
                                                                    </div>

                                                                    <div class="gx-goal-info">
                                                                        <div class="gx-name">{{ $scorerName ?: '-' }}
                                                                        </div>
                                                                        @if ($assistName)
                                                                            <div class="gx-meta">أسيست:
                                                                                {{ $assistName }}</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                @empty
                                                    <div class="gx-empty">لا توجد أحداث</div>
                                                @endforelse

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- STATISTICS --}}
                            @include('frontEnd.custom.matches.statistics') {{-- هذا ملف جديد خاص بإحصائيات المباراة (أفضل من وضع الكود هنا مباشرة) --}}


                            {{-- LINEUPS --}}
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
                                                                $initial = trim($name)
                                                                    ? mb_substr(trim($name), 0, 1)
                                                                    : '?';
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
                                                                $initial = trim($name)
                                                                    ? mb_substr(trim($name), 0, 1)
                                                                    : '?';
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
                                                            <li
                                                                class="list-group-item bg-dark text-muted border-secondary">
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
                                                            <li
                                                                class="list-group-item bg-dark text-muted border-secondary">
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

                        </div>

                        {{-- ✅ polling للتفاصيل لو LIVE --}}
                        @if (($fx['status'] ?? '') === 'LIVE')
                            @push('after-scripts')
                                <script>
                                    (function() {
                                        const url = "{{ route('match.show', ['id' => $fixtureId]) }}?refresh=1";
                                        async function tick() {
                                            try {
                                                const res = await fetch(url, {
                                                    cache: 'no-store'
                                                });
                                                if (!res.ok) return;

                                                // نجيب الصفحة ونستخرج JSON؟ (أسهل حل: سو API endpoint يرجع JSON)
                                                // ✅ الأفضل: تعمل endpoint JSON خاص للتفاصيل (أعطيك تحت)
                                            } catch (e) {}
                                        }
                                        // هنا اتركه مع endpoint JSON (أفضل)
                                    })();
                                </script>
                            @endpush
                        @endif

                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @include('frontEnd.layouts.match-details') {{-- هذا ملف جديد فيه كود الجافا سكريبت الخاص بالتحديثات الحية (أفضل من وضعه هنا مباشرة) --}}
@endpush
