<ul class="nav nav-md light dk">

    <li class="nav-item inline">
        <a class="nav-link {{ $tab == 'seasons' ? 'active' : '' }}" href="{{ route('seasons', ['league_id' => $League->id, 'tab' => 'seasons']) }}">
            <span class="text-md"><i class="material-icons">
                    &#xe8ed;</i> {{ __('backend.seasons') }}</span>
        </a>
    </li>
    <li class="nav-item inline">
        <a class="nav-link {{ $tab == 'matches' ? 'active' : '' }}" href="{{route('leaguesRounds', ['league_id' => $League->id])}}">
            <span class="text-md"><i class="material-icons">
                    &#xe8ed;</i> {{ __('backend.matches') }}</span>
        </a>
    </li>
    <li class="nav-item inline">
        <a class="nav-link {{ $tab == 'details' ? 'active' : '' }}" href="{{ route('leaguesEdit', ['id' => $League->id, 'tab' => 'details']) }}">
            <span class="text-md"><i class="material-icons">
                    &#xe8ed;</i> {{ __('backend.details') }}</span>
        </a>
    </li>
</ul>
