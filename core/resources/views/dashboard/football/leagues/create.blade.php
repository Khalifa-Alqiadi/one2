@extends('dashboard.layouts.master')
@section('title', __('backend.leagues'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('backend.add') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a>{{ __('backend.leagues') }}</a>
                </small>
            </div>
            <div class="box-body p-a-2">
                <form method="POST" action="{{ route('leaguesStore',$WebmasterSection->id) }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 form-control-label">{{ __('backend.name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="name" id="name" value="" required maxlength="191" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="country" class="col-sm-2 form-control-label">{{ __('backend.country') }}</label>
                        <div class="col-sm-10">
                            <input type="text" name="country" id="country" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="api_id" class="col-sm-2 form-control-label">{{ __('backend.apiId') }}</label>
                        <div class="col-sm-10">
                            <input type="text" name="api_id" id="api_id" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="logo" class="col-sm-2 form-control-label">{{ __('backend.logo') }}</label>
                        <div class="col-sm-10">
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t">{{ __('backend.add') }}</button>
                            <a href="{{ route('leagues',$WebmasterSection->id) }}" class="btn btn-lg btn-default m-t">{{ __('backend.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
