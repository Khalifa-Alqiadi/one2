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
                                        {{$state_code}}
                                        {{-- ✅ Box: Score (LIVE/HT/FT) --}}
                                        <div class="js-scorebox"
                                            style="font-size:28px;font-weight:800;letter-spacing:1px; display: {{ $state_code === 'NS' ? 'none' : 'block' }};">
                                            <span class="js-home">{{ $fx['score']['home'] ?? '-' }}</span>
                                            <span style="opacity:.6;margin:0 10px;">-</span>
                                            <span class="js-away">{{ $fx['score']['away'] ?? '-' }}</span>
                                        </div>

                                        {{-- ✅ Box: Not started (NS) --}}
                                        <div class="js-kickoffbox"
                                            style="display: {{ $state_code === 'NS' ? 'block' : 'none' }};">
                                            <div class="fw-bold" style="font-size:14px; opacity:.95;">لم تبدأ بعد</div>
                                            <div class="text-muted small js-kickoff">
                                                {{ $startAt ? \Carbon\Carbon::parse($startAt)->timezone('Asia/Riyadh')->format('H:i  -  Y/m/d') : '' }}
                                            </div>
                                        </div>

                                        <div class="small mt-2">
                                            <span class="badge bg-success js-status"
                                                style="display: {{ ($state_code === 'LIVE' || $state_code === 'INPLAY_2ND' || $state_code === 'INPLAY_1ST') ? 'inline-block' : 'none' }};">مباشر</span>

                                            <span class="badge bg-secondary js-ns"
                                                style="display: {{ $state_code === 'NS' ? 'inline-block' : 'none' }};">لم
                                                تبدأ</span>

                                            <span class="badge bg-secondary js-ht"
                                                style="display: {{ $state_code === 'HT' ? 'inline-block' : 'none' }};">منتصف
                                                المباراة</span>

                                            <span class="badge bg-secondary js-ft"
                                                style="display: {{ $state_code === 'FT' ? 'inline-block' : 'none' }};">النهائية</span>

                                            <span class="text-success fw-bold js-minute">
                                                {{ $status === 'LIVE' && !empty($fx['minute']) ? $fx['minute'] . "'" : '' }}
                                            </span>
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
                            @if ($state_code !== 'NS')
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
                            @include('frontEnd.custom.league-matches.partials.events')

                            {{-- STATISTICS --}}
                            @include('frontEnd.custom.league-matches.partials.statistics') {{-- هذا ملف جديد خاص بإحصائيات المباراة (أفضل من وضع الكود هنا مباشرة) --}}


                            {{-- LINEUPS --}}
                            @include('frontEnd.custom.league-matches.partials.lineups')

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
