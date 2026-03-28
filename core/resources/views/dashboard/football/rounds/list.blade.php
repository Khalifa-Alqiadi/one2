@php
    $name_var = 'name_' . @Helper::currentLanguage()->code;
    $name_var2 = 'name_' . config('smartend.default_language');
    $x = 0;
@endphp
@extends('dashboard.layouts.master')
@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">

                <h3><i class="material-icons">
                        &#xe3c9;</i> {{ __('backend.rounds') }}
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! $League->$name_var !!}</a> /
                    <a href="{{ route('seasons', ['league_id' => $League->id, 'tab' => 'seasons']) }}">{{ __('backend.seasons') }}</a> /
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline dropdown">
                        <a class="btn white b-a nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="material-icons md-18">&#xe5d4;</i> {{ __('backend.options') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale pull-right">
                            <a class="dropdown-item" href="{{ route('seasons', ['league_id' => $League->id, 'tab' => 'seasons']) }}"><i
                                    class="material-icons">&#xe31b;</i> {{ __('backend.back') }}</a>
                            <a class="dropdown-item"
                                href="{{route('leaguesRoundsAPI', ['league_id' => $League->id, 'season_id' => $season->id])}}"><i class="material-icons">&#xe863;</i>
                                {{ __('backend.update_refrech') }}</a>
                            {{-- <a class="dropdown-item" onclick="updateMatchesAPI()"><i class="material-icons">&#xe863;</i>
                                {{ __('backend.update_matches') }}</a> --}}
                        </div>
                    </li>
                </ul>
            </div>

        </div>
        <div class="b-t">
            @if ($paginatedPages->total() == 0)
                <div class="row p-a">
                    <div class="col-sm-12">
                        <div class="p-a text-center">
                            <div class="text-muted m-b">
                                <i class="fa fa-futbol-o fa-4x"></i>
                            </div>
                            <h6>{{ __('backend.noData') }}</h6>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $pageItem = $paginatedPages->first();
                    $pageTitle = $pageItem['title'] ?? '-';
                    $fixtures = $pageItem['fixtures'] ?? collect();
                    $pageType = $pageItem['type'] ?? 'round';
                    $stage = $pageItem['stage'] ?? null;
                    $round = $pageItem['round'] ?? null;
                @endphp

                <div class="p-a">
                    <h4 class="m-b-md">
                        {{ $pageTitle }}
                    </h4>

                    @if ($stage)
                        <div class="text-muted m-b-sm">
                            {{ __('backend.stage') }}:
                            <strong>{{ $stage->$name_var ?? '-' }}</strong>
                        </div>
                    @endif

                    @if ($round)
                        <div class="text-muted m-b-md">
                            {{ __('backend.round') }}:
                            <strong>{{ $round->name ?? '-' }}</strong>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('roundsUpdateAll') }}" class="dashboard-form">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered m-a-0">
                            <thead class="dker">
                                <tr>
                                    <th class="dker width20">
                                        <label class="ui-check m-a-0">
                                            <input id="checkAll" type="checkbox"><i></i>
                                        </label>
                                    </th>
                                    <th class="text-center w-64">ID</th>
                                    <th>{{ __('backend.matche') }}</th>
                                    <th class="text-center" style="width:200px;">{{ __('backend.starting_at') }}</th>
                                    <th class="text-center" style="width:200px;">{{ __('backend.status') }}</th>
                                    <th class="text-center" style="width:100px;">{{ __('backend.bulkAction') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($fixtures as $match)
                                    <tr>
                                        <td class="dker">
                                            <label class="ui-check m-a-0">
                                                <input type="checkbox" name="ids[]" value="{{ $match->id }}">
                                                <i class="dark-white"></i>
                                                <input type="hidden" name="row_ids[]" value="{{ $match->id }}"
                                                    class="form-control row_no">
                                            </label>
                                        </td>

                                        <td class="text-center">{{ $match->id }}</td>

                                        <td class="h6 nowrap">
                                            <div class="d-flex content-justify-between">
                                                <a href="{{ route('matcheRoundsEdit', ['id' => $match->id]) }}"
                                                    class="d-flex justify-content-between"
                                                    style="justify-content: space-between; display:flex">
                                                    <div>
                                                        @if ($match->homeTeam)
                                                            @if ($match->homeTeam->image_path)
                                                                <img src="{{ $match->homeTeam->image_path }}"
                                                                    style="height:30px" alt="">
                                                            @endif
                                                            <span>{{ $match->homeTeam->$name_var }}</span>
                                                        @endif
                                                    </div>
                                                    <span class="m-x-sm">vs</span>
                                                    <div>
                                                        @if ($match->awayTeam)
                                                            @if ($match->awayTeam->image_path)
                                                                <img src="{{ $match->awayTeam->image_path }}"
                                                                    style="height:30px" alt="">
                                                            @endif
                                                            <span>{{ $match->awayTeam->$name_var }}</span>
                                                        @endif
                                                    </div>
                                                </a>

                                            </div>
                                        </td>

                                        <td class="text-center">
                                            {{ $match->starting_at ? $match->starting_at->format('Y-m-d H:i') : '-' }}
                                        </td>

                                        <td class="text-center">
                                            @if ($match->is_finished)
                                                <span class="text-info">{{ __('backend.finished') }}</span>
                                            @elseif ($match->starting_at && $match->starting_at > now())
                                                <span class="text-success">{{ __('backend.not_started_yet') }}</span>
                                            @else
                                                <span class="text-warning">{{ __('backend.live_now') ?? 'Live' }}</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <i class="material-icons">&#xe5d4;</i>
                                                    {{ __('backend.options') }}
                                                </button>
                                                <div class="dropdown-menu pull-right">
                                                    @if (@Auth::user()->permissionsGroup->edit_status)
                                                        <a class="dropdown-item"
                                                            href="{{ route('matcheRoundsEdit', ['id' => $match->id]) }}">
                                                            <i class="material-icons">&#xe3c9;</i>
                                                            {{ __('backend.edit') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            {{ __('backend.noData') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <footer class="dker p-a">
                        <div class="row">
                            <div class="col-sm-3 text-center">
                                <small class="text-muted inline m-t-sm m-b-sm">
                                    {{ __('backend.showing') }}
                                    {{ $paginatedPages->firstItem() }}
                                    - {{ $paginatedPages->lastItem() }}
                                    {{ __('backend.of') }}
                                    <strong>{{ $paginatedPages->total() }}</strong>
                                    {{ __('backend.records') }}
                                </small>
                            </div>

                            <div class="col-sm-9 text-right text-center-xs">
                                {!! $paginatedPages->appends(request()->query())->links() !!}
                            </div>
                        </div>
                    </footer>
                </form>
            @endif
        </div>

    </div>
@endsection
@push('after-scripts')
    <script>
        function updateRoundAPI() {
            $('#updateRoundModal').modal('show');
        }

        function updateMatchesAPI() {
            $('#updateMatchesModal').modal('show');
        }
    </script>
