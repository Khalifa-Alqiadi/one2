@extends('dashboard.layouts.master')
@section('title', __('backend.adsBannersSettings'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('backend.addNewBannerType') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="{{ route('Banners') }}">{{ __('backend.adsBanners') }}</a> /
                    <a href="">{{ __('backend.adsBannersSettings') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("WebmasterBanners")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body">
                <form method="POST" action="{{ route("WebmasterBannersStore") }}" class="dashboard-form">
                    @csrf
                    @foreach(Helper::languagesList() as $ActiveLanguage)
                        <div class="form-group row">
                            <label for="title_{{ @$ActiveLanguage->code }}"
                                class="col-sm-2 form-control-label">{!!  __('backend.sectionName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                            </label>
                            <div class="col-sm-10">
                                <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" id="title_{{ @$ActiveLanguage->code }}" value="" required maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group row">
                        <label for="type1"
                               class="col-sm-2 form-control-label">{!!  __('backend.sectionType') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="md-check">
                                    <input type="radio" name="type" value="0" class="has-value" checked id="type1">
                                    <i class="primary"></i>
                                    {{ __('backend.sectionTypeCode') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="type" value="1" class="has-value" id="type2">
                                    <i class="primary"></i>
                                    {{ __('backend.sectionTypePhoto') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="type" value="2" class="has-value" id="type3">
                                    <i class="primary"></i>
                                    {{ __('backend.sectionTypeVideo') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="width"
                               class="col-sm-2 form-control-label">{!!  __('backend.size') !!}</label>
                        <div class="col-sm-2">
                            <input type="number" name="width" id="width" value="" class="form-control" placeholder="{{ __('backend.width') }}" min="0" required>
                        </div>
                        <div class="col-sm-1 text-center" style="width: 30px !important;padding-top: 10px;">
                            X
                        </div>
                        <div class="col-sm-2">
                            <input type="number" name="height" id="height" value="" class="form-control" placeholder="{{ __('backend.height') }}" min="0" required>
                        </div>
                        <div class="col-sm-1 text-center" style="width: 30px !important;padding-top: 10px;">
                            PX
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="desc_status1"
                               class="col-sm-2 form-control-label">{!!  __('backend.descriptionBox') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="md-check">
                                    <input type="radio" name="desc_status" value="1" class="has-value" checked id="desc_status1">
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="desc_status" value="0" class="has-value" id="desc_status2">
                                    <i class="danger"></i>
                                    {{ __('backend.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link_status1"
                               class="col-sm-2 form-control-label">{!!  __('backend.linkBox') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="md-check">
                                    <input type="radio" name="link_status" value="1" class="has-value" checked id="link_status1">
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="link_status" value="0" class="has-value" id="link_status2">
                                    <i class="danger"></i>
                                    {{ __('backend.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="icon_status1"
                               class="col-sm-2 form-control-label">{!!  __('backend.iconPicker') !!}</label>
                        <div class="col-sm-10">
                            <div class="radio">
                                <label class="md-check">
                                    <input type="radio" name="icon_status" value="1" class="has-value" id="icon_status1">
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                                &nbsp; &nbsp;
                                <label class="md-check">
                                    <input type="radio" name="icon_status" value="0" class="has-value" checked id="icon_status2">
                                    <i class="primary"></i>
                                    {{ __('backend.yes') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.add') !!}</button>
                            <a href="{{route("WebmasterBanners")}}"
                               class="btn btn-lg btn-default m-t"><i class="material-icons">
                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
