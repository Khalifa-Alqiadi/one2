<div class="card text-light shadow-sm" style="border-radius:14px;">
    @php
        $status = $fx['status'] ?? 'NS';
        $p = $fx['probabilities'] ?? null;

        $pHome = (int) data_get($p, 'home', 0);
        $pDraw = (int) data_get($p, 'draw', 0);
        $pAway = (int) data_get($p, 'away', 0);
    @endphp


    <h3 class="card-title mb-5 pb-4 border-bottom border-2">{{ __('frontend.win_probability') }}</h3>

    @if ($p)
        <div class="card-header border-0 p-0">
            <div class="row">
                <div class="col-4">
                    <div class=" text-center">
                        {{$fx['away']['name'] ?? 'الضيوف'}}
                    </div>
                </div>
                <div class="col-4 border-start border-end" style="border-color:rgb(58, 58, 58) !important">
                    <div class=" text-center">
                        {{__('frontend.draw')}}
                    </div>
                </div>
                <div class="col-4">
                    <div class=" text-center">
                        {{$fx['home']['name'] ?? 'المضيف'}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body position-relative px-0 pt-0">
            <div class="row">
                <div class="col-4 pt-3">
                    <div class=" text-center gx-ns-perc-val js-p-away">
                        {{ $pAway }}%
                    </div>
                    <div class="gx-ns-bar">
                        <div class="gx-ns-bar-away js-bar-away" style="width: {{ $pAway }}%"></div>
                    </div>
                </div>
                <div class="col-4 border-start border-end pt-3" style="border-color:rgb(58, 58, 58) !important">
                    <div class="text-center gx-ns-perc-val js-p-draw">
                        {{ $pDraw }}%
                    </div>
                    <div class="gx-ns-bar">
                        <div class="gx-ns-bar-draw js-bar-draw" style="width: {{ $pDraw }}%"></div>
                    </div>
                </div>
                <div class="col-4 pt-3">
                    <div class="text-center gx-ns-perc-val js-p-home">
                        {{ $pHome }}%
                    </div>
                    <div class="gx-ns-bar">
                        <div class="gx-ns-bar-home js-bar-home" style="width: {{ $pHome }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-muted text-center small">{{ __('frontend.no_win_probability') }}</div>
    @endif
</div>
