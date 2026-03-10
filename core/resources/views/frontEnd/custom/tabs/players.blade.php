<div class="tab-pane fade" id="t-players">
    @if (!empty($playersErr))
        <div class="alert alert-danger">{{ $playersErr }}</div>
    @endif

    @forelse(($playersBlocks ?? []) as $block)
        @php
            // squad ممكن يجي كـ data أو array أو object
            $rawSquad = $block['squad'] ?? [];
            $squadArr = data_get($rawSquad, 'data', $rawSquad);
            $squad = collect(is_array($squadArr) ? $squadArr : []);
        @endphp

        <div class="cardx mb-3">
            <div class="p-2 border-bottom d-flex align-items-center gap-2">
                <img class="team-logo" src="{{ $block['logo'] ?? '' }}" alt="">
                <strong>{{ $block['team'] ?? '' }}</strong>
                <span class="text-muted small ms-auto">{{ $squad->count() }}</span>
            </div>

            <div class="p-2">
                @if ($squad->isEmpty())
                    <div class="text-muted small">
                        {{ $locale == 'ar'
                            ? 'لا توجد بيانات لاعبين (قد تكون غير متاحة في اشتراكك)'
                            : 'No squad data (may be unavailable in your plan)' }}
                    </div>
                @else
                    <div class="row">
                        @foreach ($squad->take(24) as $p)
                            @php
                                // أسماء اللاعب ممكن تكون في أكثر من مكان
                                $playerName =
                                    data_get($p, 'player_name')
                                    ?? data_get($p, 'player.data.display_name')
                                    ?? data_get($p, 'player.data.name')
                                    ?? data_get($p, 'player.name')
                                    ?? '-';

                                $position =
                                    data_get($p, 'position.name')
                                    ?? data_get($p, 'position.data.name')
                                    ?? data_get($p, 'position')
                                    ?? '';
                            @endphp

                            <div class="col-md-4 mb-2">
                                <div class="border rounded p-2">
                                    <strong>{{ $playerName }}</strong>
                                    <div class="text-muted small">{{ $position }}</div>
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
