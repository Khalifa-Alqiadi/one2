@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
@endphp
<a href="{{ route('match.show', ['id' => $match->id]) }}"
    class="card bg-transparent h-100 gx-fixture-card {{ $isTimeLive ? 'active' : '' }}" id="fixture-{{ $match->id }}"
    data-live="{{ $isTimeLive ? 1 : 0 }}">
    <div class="card-header d-flex align-items-center justify-content-between bg-transparent border-0 p-0 mb-3">
        @if ($match->league)
            <span>{{ $match->league->$name_var }}</span>
        @endif
        <span class="js-minute">
            @if ($isTimeLive && $minute)
                {{ $minute }}
            @endif
        </span>
    </div>
    <div class="box-match row ">
        <div class="col-4">
            <div class="team d-flex flex-column align-items-center">
                @if ($match->homeTeam)
                    <div class="d-flex flex-column align-items-center">
                        @if ($match->homeTeam->image_path)
                            <div class="image d-flex align-items-center justify-content-center">
                                <img src="{{ $match->homeTeam->image_path }}" style="height:30px" alt="">
                            </div>
                        @endif
                        <span class="mt-2 text-center">{{ $match->homeTeam->$name_var }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-4">
            <div class="details text-center">
                @if ($isFinished || $isTimeLive)
                    <div class="d-flex gx-score align-items-center justify-content-center gap-2">
                        <div class="goals js-home-score">
                            <span class="">{{ $match->home_score }}</span>
                        </div>
                        <span class="m-x-sm">-</span>
                        <div class="goals js-away-score">
                            <span class="">{{ $match->away_score }}</span>
                        </div>
                    </div>
                @else
                    <div>
                        <span class="m-x-sm">vs</span>
                    </div>
                @endif
                <div class="status">
                    <span class="js-live-badge">
                        @if ($isFinished)
                            {{ __('frontend.finished') }}
                        @elseif ($isTimeLive)
                            {{ __('frontend.live') }}
                        @else
                            {{ __('frontend.not_started') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="team d-flex flex-column align-items-center">
                @if ($match->awayTeam)
                    <div class="d-flex flex-column align-items-center">
                        @if ($match->awayTeam->image_path)
                            <div class="image d-flex align-items-center justify-content-center">
                                <img src="{{ $match->awayTeam->image_path }}" style="height:30px" alt="">
                            </div>
                        @endif
                        <span class="mt-2 text-center">{{ $match->awayTeam->$name_var }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body border-top mt-3 pb-0 d-flex align-items-center justify-content-between">
        <span>
            {!! Helper::day_name($dt) !!}
            @if ($timeLabel)
                • {{ $timeLabel }}
            @endif
        </span>
        <p class="m-0">
            {{ __('frontend.match_show') }}
            <i class="fas fa-arrow-{{ Helper::isRTL() ? 'left' : 'right' }} mx-1"></i>
        </p>
    </div>
</a>
