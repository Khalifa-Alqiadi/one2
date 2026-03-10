@php
    $title_var = 'title_' . @Helper::currentLanguage()->code;
    $title_var2 = 'title_' . config('smartend.default_language');
@endphp
@extends('dashboard.layouts.master')
@section('title', __('backend.leagues'))
@section('content')
    <div class="padding">
        <div class="box m-b-0">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{ __('backend.leagues') }}</a>
                </small>
            </div>
        </div>

        <div class="box nav-active-border b-info">
            @include('dashboard.leagues.tabs')
            <div class="tab-content clear b-t">
                <div class="tab-pane active" id="tab_details">
                    <div class="box-body p-a-2">
                        <form method="POST" action="{{ route('leaguesUpdate',['id'=>$League->id]) }}" class="dashboard-form" enctype="multipart/form-data">
                            @csrf

                            @foreach(Helper::languagesList() as $ActiveLanguage)
                                @if($ActiveLanguage->box_status)
                                    <div class="form-group row">
                                        <label for="title_{{ @$ActiveLanguage->code }}"
                                               class="col-sm-2 form-control-label">{!!  __('backend.name') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" autocomplete="off" name="name_{{ @$ActiveLanguage->code }}" id="name_{{ @$ActiveLanguage->code }}" value="{{ $League->{'name_'.@$ActiveLanguage->code} }}" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="form-group row">
                                <label for="country" class="col-sm-2 form-control-label">{{ __('backend.country') }}</label>
                                <div class="col-sm-10">
                                    <select name="country_id" id="" class="form-control c-select">
                                        <option value="">{{__('backend.selectCountry')}}</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->sport_id}}" {{$League->country_id == $country->sport_id ? 'selected' : ''}}>{{$country->$title_var}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="logo" class="col-sm-2 form-control-label">{{ __('backend.logo') }}</label>
                                <div class="col-sm-10">
                                    @if($League->League_image)
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="League_logo" class="col-sm-4 box p-a-xs">
                                                    <a target="_blank" href="{{ $League->League_image }}" class="img-responsive">{{ $League->League_image }}</a>
                                                    <br>
                                                    <a onclick="document.getElementById('League_logo').style.display='none';document.getElementById('logo_delete').value='1';document.getElementById('undo').style.display='block';" class="btn btn-sm btn-default">{{ __('backend.delete') }}</a>
                                                </div>
                                                <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                                    <a onclick="document.getElementById('League_logo').style.display='block';document.getElementById('logo_delete').value='0';document.getElementById('undo').style.display='none';"><i class="material-icons">&#xe166;</i> {{ __('backend.undoDelete') }}</a>
                                                </div>
                                                <input type="hidden" name="logo_delete" value="0" id="logo_delete">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status1" class="col-sm-2 form-control-label">{{ __('backend.status') }}</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <label class="md-check">
                                            <input type="radio" name="status" value="1" class="has-value" {{ ($League->status==1)?"checked":"" }} id="status1">
                                            <i class="primary"></i>
                                            {{ __('backend.active') }}
                                        </label>
                                        &nbsp; &nbsp;
                                        <label class="md-check">
                                            <input type="radio" name="status" value="0" class="has-value" {{ ($League->status==0)?"checked":"" }} id="status2">
                                            <i class="danger"></i>
                                            {{ __('backend.notActive') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row m-t-md">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-lg btn-primary m-t">{{ __('backend.update') }}</button>
                                    <a href="{{ route('leagues') }}" class="btn btn-lg btn-default m-t">{{ __('backend.cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
