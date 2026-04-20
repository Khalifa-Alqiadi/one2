<div class="tab-pane fade players-list" id="t-players_list" role="tabpanel">
    <div class="matches matches-home">
        <div class="row">
            @php $i = 0; @endphp
            {{-- @if(count($team->players) > 0) --}}
            @forelse ($team->players as $player)
                <div class="col-md-6 mb-4">
                    <a href="{{route('players.details', ['id' => $player->id])}}" class="d-flex align-items-center">
                        <div>
                            <img src="{{$player->image_path}}" alt="{{$player->$name_var}}">
                        </div>
                        <div class="mx-3">
                            <h4 class="mb-1">{{$player->$name_var}}</h4>
                            <span>{{$player->country->$title_var}}</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col w-50">
                    <div class="text-center mb-0">
                        {{ __('frontend.no_matches_found') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
