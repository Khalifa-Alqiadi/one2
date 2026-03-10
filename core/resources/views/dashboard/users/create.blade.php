@extends('dashboard.layouts.master')
@section('title', __('backend.usersPermissions'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe02e;</i> {{ __('backend.newUser') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="">{{ __('backend.settings') }}</a> /
                    <a href="">{{ __('backend.usersPermissions') }}</a>
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("users")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body">
                <form method="POST" action="{{ route("usersStore") }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label for="account_name"
                               class="col-sm-2 form-control-label">{!!  __('backend.fullName') !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="name" id="account_name" value="" required maxlength="100"
                                   placeholder="" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="account_email"
                               class="col-sm-2 form-control-label">{!!  __('backend.loginEmail') !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="email" autocomplete="off" name="email" id="account_email" value="" required maxlength="100"
                                   placeholder="" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password"
                               class="col-sm-2 form-control-label">{!!  __('backend.loginPassword') !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="account_photo"
                               class="col-sm-2 form-control-label">{!!  __('backend.personalPhoto') !!}</label>
                        <div class="col-sm-10">
                            <input type="file" name="photo" id="account_photo" accept="image/*" class="form-control"/>
                            <small>
                                <i class="material-icons">&#xe8fd;</i>
                                {!!  __('backend.imagesTypes') !!}
                            </small>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="permissions1"
                               class="col-sm-2 form-control-label">{!!  __('backend.Permission') !!}</label>
                        <div class="col-sm-10">
                            <select name="permissions_id" id="permissions_id" required class="form-control c-select">
                                <option value="">- - {!!  __('backend.selectPermissionsType') !!} - -</option>
                                @foreach ($Permissions as $Permission)
                                    <option value="{{ $Permission->id  }}">{{ $Permission->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.add') !!}</button>
                            <a href="{{route("users")}}"
                               class="btn btn-lg btn-default m-t"><i class="material-icons">
                                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
