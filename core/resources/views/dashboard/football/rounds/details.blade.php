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
                        &#xe3c9;</i> {{ __('backend.details') }}
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="{{ route('leagues', ['id' => $match->League->id]) }}">{!! $match->League->$name_var !!}</a> /
                    <a>{{ __('backend.rounds') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline dropdown">
                        <a class="btn white b-a nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="material-icons md-18">&#xe5d4;</i> {{ __('backend.options') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale pull-right">
                            <a class="dropdown-item" href="{{ route('leaguesRounds', ['id' => $match->League->id]) }}"><i
                                    class="material-icons">&#xe31b;</i> {{ __('backend.back') }}</a>

                        </div>
                    </li>
                </ul>
            </div>

        </div>
        <div class="box nav-active-border b-info">
            <div class="tab-content clear b-t">
                <div class="tab-pane active" id="tab_details">
                    <div class="box-body p-a-2">
                        <div class="row">
                            <div class="col-md-2">
                                <label  class="col-sm-2 form-control-label">{{ __('backend.matche') }}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="box p-a-1">
                                    <div class="box-header2">
                                        <div class="team">
                                            @if ($match->homeTeam)
                                                @if ($match->homeTeam->image_path)
                                                    <img src="{{ $match->homeTeam->image_path }}" style="height:30px"
                                                        alt="">
                                                @endif
                                                <span class="m-t-1">{{ $match->homeTeam->$name_var }}</span>
                                            @endif
                                        </div>
                                        <div class="goals">
                                            <h4>{{ $match->home_goals }}</h4>
                                        </div>
                                        <span class="m-x-sm">vs</span>
                                        <div class="goals">
                                            <h4>{{ $match->away_goals }}</h4>
                                        </div>
                                        <div class="team">
                                            @if ($match->awayTeam)
                                                @if ($match->awayTeam->image_path)
                                                    <img src="{{ $match->awayTeam->image_path }}" style="height:30px"
                                                        alt="">
                                                @endif
                                                <span class="m-t-1">{{ $match->awayTeam->$name_var }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('matchUpdate', ['id' => $match->id]) }}"
                            class="dashboard-form" enctype="multipart/form-data">
                            @csrf

                            @if($match->starting_at > $today)
                                <div class="form-group row">
                                    <label for="is_home1" class="col-sm-2 form-control-label">{{ __('backend.is_home') }}</label>
                                    <div class="col-sm-10">
                                        <div class="radio">
                                            <label class="md-check">
                                                <input type="radio" name="is_home" value="1" class="has-value" {{ ($match->is_home==1)?"checked":"" }} id="is_home1">
                                                <i class="primary"></i>
                                                {{ __('backend.active') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="is_home" value="0" class="has-value" {{ ($match->is_home==0)?"checked":"" }} id="is_home2">
                                                <i class="danger"></i>
                                                {{ __('backend.notActive') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="is_slider1" class="col-sm-2 form-control-label">{{ __('backend.is_slider') }}</label>
                                    <div class="col-sm-10">
                                        <div class="radio">
                                            <label class="md-check">
                                                <input type="radio" name="is_slider" value="1" class="has-value" {{ ($match->is_slider==1)?"checked":"" }} id="is_slider1">
                                                <i class="primary"></i>
                                                {{ __('backend.active') }}
                                            </label>
                                            &nbsp; &nbsp;
                                            <label class="md-check">
                                                <input type="radio" name="is_slider" value="0" class="has-value" {{ ($match->is_slider==0)?"checked":"" }} id="is_slider2">
                                                <i class="danger"></i>
                                                {{ __('backend.notActive') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row m-t-md">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit"
                                        class="btn btn-lg btn-primary m-t">{{ __('backend.update') }}</button>
                                    <a href="{{ route('leaguesRounds', ['league_id' => $match->league->id, 'season_id' => $match->season_id]) }}"
                                        class="btn btn-lg btn-default m-t">{{ __('backend.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-styles')
    <style>
        .box-header2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .team {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column
        }

        .goals {
            display: flex;
            align-items: center;
        }
    </style>
@endpush
