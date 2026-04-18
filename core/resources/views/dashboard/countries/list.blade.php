@extends('dashboard.layouts.master')
@section('title', __('backend.countries'))
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/datatables/datatables.min.css') }}">
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3>{!! __('backend.countries') !!}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{!! __('backend.countries') !!}</a>
                </small>
            </div>
            <div class="box-tool box-tool-lg">
                <ul class="nav" style="display: flex;justify-content: space-around;">
                    <li class="nav-item inline">
                        <form method="GET" action="{{ route('countries') }}" class="dashboard-form w-md" id="filter_form">
                            @csrf
                            <div class="form-group l-h m-a-0">
                                <div class="input-group">
                                    <input type="text" name="find_q" id="countries_find_q" class="form-control p-x"
                                           autocomplete="off" placeholder="{{ __('backend.searchCountries') }}...">
                                    <span
                                        class="input-group-btn"><button type="submit" class="btn white b-a no-shadow"><i
                                                class="fa fa-search"></i></button></span></div>
                            </div>
                        </form>
                    </li>
                    <li class="nav-item inline">
                        @if (@Auth::user()->permissionsGroup->add_status)
                            <a class="btn btn-fw info w-100" style="overflow: hidden"
                                href="{{ route('countriesUpdateAPI') }}">
                                <i class="material-icons">&#xe02e;</i>
                                &nbsp; {{ __('backend.update_refrech') }}</a>
                        @endif
                    </li>
                </ul>
            </div>
            <div>
                <form method="POST" action="{{ route('countriesUpdateAll') }}" class="dashboard-form" id="table_form">
                    @csrf
                    <div class="table-responsive" style="overflow: inherit">
                        <table class="table table-bordered" style="width: 100%" id="countries_table">
                            <thead class="dker">
                            <th style="width:90px;">#</th>
                            <th>{{ __('backend.country') }}</th>
                            <th style="width:120px;">{{ __('backend.countryCode') }}</th>
                            <th style="width:120px;">{{ __('backend.countryTel') }}</th>
                            <th style="width:160px;">{{ __('backend.number_teams') }}</th>
                            <th class="text-center" style="width:60px;">{{ __('backend.options') }}</th>
                            </thead>
                            <tbody>
                                    <?php
                                    $title_var = "title_".@Helper::currentLanguage()->code;
                                    $title_var2 = "title_".config('smartend.default_language');
                                    $x = 0;
                                    ?>
                                @foreach($countries as $country)
                                        <?php
                                        $x++;
                                        if ($country->$title_var != "") {
                                            $title = $country->$title_var;
                                        } else {
                                            $title = $country->$title_var2;
                                        }
                                        ?>
                                    <tr>

                                        <td class="text-center">{{ $country->id }}</td>
                                        <td class="h6 nowrap">
                                            {{-- <a href="{{ route("countriesEdit",["id"=>$country->id]) }}"> --}}
                                                {{ $title }}
                                            {{-- </a> --}}
                                        </td>
                                        <td class="text-center">
                                            {{ $country->code }}
                                        </td>
                                        <td class="text-center">
                                            {{ $country->tel }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('countryTeams', ['country_id' => $country->id])}}">
                                                {{ $country->teams->count() }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown {{ (($x+1) >= count($countries))?"dropup":"" }}">
                                                <button type="button" class="btn btn-sm light dk dropdown-toggle"
                                                        data-toggle="dropdown"><i class="material-icons">&#xe5d4;</i>
                                                    {{ __('backend.options') }}
                                                </button>
                                                <div class="dropdown-menu pull-right">
                                                    @if(@Auth::user()->permissionsGroup->edit_status)
                                                        <a class="dropdown-item"
                                                           href="{{ route("updateTeamsByCountry",["country_id"=>$country->id]) }}"><i
                                                                class="material-icons">&#xe3c9;</i> {{ __('backend.update_teams') }}
                                                        </a>
                                                        <a class="dropdown-item"
                                                           href="{{ route("updatePlayersByCountry",["country_id"=>$country->id]) }}"><i
                                                                class="material-icons">&#xe3c9;</i> {{ __('backend.update_players') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                        </table>
                    </div>
                    <footer class="dker p-a">
                        <div class="row">

                            <div class="col-sm-3 text-center">
                                <small class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }}
                                    {{ $countries->firstItem() }}
                                    -{{ $countries->lastItem() }} {{ __('backend.of') }}
                                    <strong>{{ $countries->total() }}</strong> {{ __('backend.records') }}</small>
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $countries->links() !!}
                            </div>
                        </div>
                    </footer>
                </form>
            </div>
        </div>
    </div>

    {{-- @if(@Auth::user()->permissionsGroup->delete_status)
        <div id="delete-country" class="modal fade" data-backdrop="true">
            <div class="modal-dialog" id="animate">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                    </div>
                    <div class="modal-body text-center p-lg">
                        <h5 class="m-b-0">
                            {{ __('backend.confirmationDeleteMsg') }}
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md"
                                data-dismiss="modal">{{ __('backend.no') }}</button>
                        <button type="button" id="country_delete_btn" row-id=""
                                class="btn danger p-x-md">{{ __('backend.yes') }}</button>
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div>
    @endif --}}

    <div id="update-country" class="modal fade" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('countriesUpdate') }}" class="dashboard-form" id="update-country-form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="material-icons">&#xe3c9;</i> <span class="modal-box-title">{!! __('backend.editCountry') !!}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-a-0"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">{!! __('backend.cancel') !!}</button>
                        <button type="submit" id="update-country-form-submit" class="btn info p-x-md"><i class="material-icons">&#xe31b;</i> {!! __('backend.save') !!}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
