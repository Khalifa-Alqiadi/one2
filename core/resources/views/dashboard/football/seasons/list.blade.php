
<?php
$name_var = 'name_' . @Helper::currentLanguage()->code;
$x = 0;
?>
@extends('dashboard.layouts.master')

@section('content')
<div class="padding">
        <div class="box">
            <div class="box-header dker">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <h3>{{ __('backend.seasons') }} </h3>
                        <small>
                            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                            <a href="{{ route('leaguesEdit', ['id' => $League->id]) }}">{{ $League->$name_var }}</a> /

                            <a>{{ __('backend.seasons') }} </a>
                        </small>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="row">
                            <div class="col-sm-7">
                                <form method="GET" action="{{ route('seasons') }}" class="form-inline" role="search">
                                    <div class="form-group">
                                        <div class="input-group"><input type="text" name="q"
                                                value="{{ @$_GET['q'] }}" class="form-control p-x" autocomplete="off"
                                                placeholder="{{ __('backend.searchIn') . ' ' . __('backend.seasons') }}">
                                            <span class="input-group-btn"><button type="submit"
                                                    class="btn white b-a no-shadow"><i
                                                        class="fa fa-search"></i></button></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-5">
                                @if (@Auth::user()->permissionsGroup->add_status)
                                    <a class="btn btn-fw info w-100" style="overflow: hidden"
                                        href="{{ route('seasonsUpdate', ['league_id' => $League->id]) }}">
                                        <i class="material-icons">&#xe02e;</i>
                                        &nbsp; {{ __('backend.update_refrech') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box nav-active-border b-primary">
                    @include('dashboard.football.leagues.tabs')
                </div>
            </div>

            <div class="b-t">
                @if ($Seasons->total() == 0)
                    <div class="row p-a">
                        <div class="col-sm-12">
                            <div class=" p-a text-center ">
                                <div class="text-muted m-b"><i class="fa fa-futbol-o fa-4x"></i></div>
                                <h6>{{ __('backend.noData') }}</h6>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($Seasons->total() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered m-a-0">
                            <thead class="dker">
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('backend.season') }}</th>
                                    <th class="text-center">{{ __('backend.starting_at') ?? 'Starting At' }}</th>
                                    <th class="text-center">{{ __('backend.ending_at') ?? 'Ending At' }}</th>
                                    <th class="text-center">{{ __('backend.is_current') ?? 'Current' }}</th>
                                    <th class="text-center" style="width:100px;">{{ __('backend.bulkAction') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Seasons as $Season)
                                    <tr>
                                        <td>{{ $Season->id }}</td>
                                        <td class="h6 nowrap">
                                            {{ $Season->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $Season->starting_at }}
                                        </td>
                                        <td class="text-center">
                                            {{ $Season->ending_at }}
                                        </td>
                                        <td class="text-center">
                                            {{ $Season->is_current ? 'Yes' : 'No' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown {{ (($x+1) >= count($Seasons))?"dropup":"" }}">
                                                <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                        data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                                    {{ __('backend.options') }}
                                                </button>
                                                <div class="dropdown-menu pull-right">
                                                    @if(@Auth::user()->permissionsGroup->edit_status)
                                                        <a class="dropdown-item"
                                                            href="{{route('leaguesRoundsAPI', ['league_id' => $League->id, 'season_id' => $Season->id])}}"><i
                                                                class="material-icons">&#xe3c9;</i> {{ __('backend.updateMatches') }}
                                                        </a>
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{ route("league.rounds",["id"=>$League->id]) }}"><i
                                                                class="material-icons">&#xe8f4;</i> {{ __('backend.preview') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        {{-- <td class="text-center">
                                            <a class=""
                                                href="{{route('leaguesRounds', ['league_id' => $League->id, 'season_id' => $Season->id])}}"
                                                ><i
                                                    class="material-icons">&#xe8f4;</i>
                                            </a>
                                        </td> --}}
                                        {{-- <td class="text-center">{{ $League->sport_id }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <footer class="dker p-a">
                        <div class="row">

                            <div class="col-sm-3 text-center">
                                <small class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }}
                                    {{ $Seasons->firstItem() }}
                                    -{{ $Seasons->lastItem() }} {{ __('backend.of') }}
                                    <strong>{{ $Seasons->total() }}</strong> {{ __('backend.records') }}</small>
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $Seasons->links() !!}
                            </div>
                        </div>
                    </footer>
                @endif

            </div>
        </div>
    </div>
@endsection
