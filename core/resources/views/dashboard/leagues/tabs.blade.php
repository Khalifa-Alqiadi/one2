<ul class="nav nav-md light dk">
    <li class="nav-item inline">
        <a class="nav-link {{ $tab == 'details' ? 'active' : '' }}" href="{{ route('leaguesEdit', ['id' => $League->id, 'tab' => 'details']) }}">
            <span class="text-md"><i class="material-icons">
                    &#xe8ed;</i> {{ __('backend.details') }}</span>
        </a>
    </li>
    <li class="nav-item inline">
        <a class="nav-link {{ $tab == 'rounds' ? 'active' : '' }}" href="{{ route('leaguesRounds', ['id' => $League->id, 'tab' => 'rounds']) }}">
            <span class="text-md"><i class="material-icons">
                    &#xe8ed;</i> {{ __('backend.rounds') }}</span>
        </a>
    </li>
</ul>
