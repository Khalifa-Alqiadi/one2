@php
    $events = $fx['events'] ?? [];
    if (is_array($events) && isset($events['data']) && is_array($events['data'])) {
        $events = $events['data'];
    }

    $minuteToNumber = function ($e) {
        $rawMinute = $e['minute'] ?? null;

        if (is_numeric($rawMinute)) {
            return (int) $rawMinute;
        }

        $label = (string) ($e['minute_label'] ?? '');

        if (preg_match('/\d+/', $label, $m)) {
            return (int) $m[0];
        }

        return 0;
    };

    // $timeline = collect($events)->filter(fn($e) => is_array($e))->sortByDesc(fn($e) => $minuteToNumber($e))->values();
@endphp

<div class="mt-5">

    @foreach ($events as $e)
        @php
            $kind = (string) ($e['kind'] ?? 'other');
            $minute = $e['minute_label'] ?? '';
            $scorerName = (string) ($goal['scorer_name'] ?? ($e['player_name'] ?? ''));
        @endphp

        {{-- GOAL CARD --}}
        @if ($teamid == $e['participant_id'])
            @if ($kind === 'goal')
                <div class="badge text-secondary text-start d-block">
                    {{ $scorerName ?: '-' }} {{ $minute ?: '' }}
                </div>
            @endif
        @endif

        {{-- YELLOW CARD --}}

        {{-- RED CARD --}}
    @endforeach
    @foreach ($events as $e)
        @php
            $kind = (string) ($e['kind'] ?? 'other');
            $minute = $e['minute_label'] ?? '';

            $playerName = (string) ($e['player_name'] ?? '');

            $eventTypeName = mb_strtolower((string) ($e['type_name'] ?? ($e['event_name'] ?? ($e['name'] ?? ''))));

            $isYellowCard = $kind === 'yellow_card' || str_contains($eventTypeName, 'yellow');
            $isRedCard = $kind === 'red_card' || str_contains($eventTypeName, 'red');
        @endphp

        {{-- GOAL CARD --}}
        @if ($teamid == $e['participant_id'])
            @if ($isRedCard)
                <div class="badge text-secondary text-start d-block mt-3">
                    {{ $playerName ?: '-' }} {{ $minute ?: '' }}: <span class="gx-icon">🟥</span>
                </div>
            @endif
        @endif

        {{-- YELLOW CARD --}}

        {{-- RED CARD --}}
    @endforeach

</div>
