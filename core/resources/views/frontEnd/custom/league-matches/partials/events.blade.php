{{-- @if ($status !== 'NS') --}}
    <div class="tab-pane fade " id="t-events" role="tabpanel">
        <div class="card bg-dark text-light border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body">
                @php
                    // events ممكن تجي مباشرة أو داخل data
                    $events = $fx['events'] ?? [];
                    if (is_array($events) && isset($events['data']) && is_array($events['data'])) {
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

                            $inInitial = trim($inName) ? mb_substr(trim($inName), 0, 1) : '?';
                            $outInitial = trim($outName) ? mb_substr(trim($outName), 0, 1) : '?';

                            // GOAL (اختياري)
                            $goal = $e['goal'] ?? [];
                            $scorerName = (string) ($goal['scorer_name'] ?? ($e['player_name'] ?? ''));
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
                                            <img src="{{ $media['main'] }}" alt="" loading="lazy"
                                                onerror="this.remove();">
                                        </div>
                                    @endif

                                    <div class="gx-media-thumbs">
                                        @if (!empty($media['a']))
                                            <div class="gx-media-thumb">
                                                <img src="{{ $media['a'] }}" alt="" loading="lazy"
                                                    onerror="this.remove();">
                                            </div>
                                        @endif
                                        @if (!empty($media['b']))
                                            <div class="gx-media-thumb">
                                                <img src="{{ $media['b'] }}" alt="" loading="lazy"
                                                    onerror="this.remove();">
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

                                        <div class="gx-avatar gx-in-ring" title="{{ $inName }}">
                                            @if ($inImg)
                                                <img src="{{ $inImg }}" alt="" loading="lazy"
                                                    onerror="this.remove();">
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

                                        <div class="gx-avatar gx-out-ring" title="{{ $outName }}">
                                            @if ($outImg)
                                                <img src="{{ $outImg }}" alt="" loading="lazy"
                                                    onerror="this.remove();">
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
                                                <img src="{{ $scorerImg }}" alt="" loading="lazy"
                                                    onerror="this.remove();">
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
{{-- @endif --}}
