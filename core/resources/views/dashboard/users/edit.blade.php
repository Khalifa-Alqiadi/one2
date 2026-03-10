@extends('dashboard.layouts.master')
@section('title', __('backend.usersPermissions'))
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> {{ __('backend.editUser') }}</h3>
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
                <form method="POST" action="{{ route('usersUpdate',$Users->id) }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label for="account_name"
                               class="col-sm-2 form-control-label">{!!  __('backend.fullName') !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="text" autocomplete="off" name="name" id="account_name" value="{{ $Users->name }}" required maxlength="100"
                                   placeholder="" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="account_email"
                               class="col-sm-2 form-control-label">{!!  __('backend.loginEmail') !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="email" autocomplete="off" name="email" id="account_email" value="{{ $Users->email }}" required maxlength="100"
                                   placeholder="" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password"
                               class="col-sm-2 form-control-label">{!!  __('backend.loginPassword') !!}
                        </label>
                        <div class="col-sm-10">
                            <input type="password" name="password" minlength="6" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="account_photo"
                               class="col-sm-2 form-control-label">{!!  __('backend.topicPhoto') !!}</label>
                        <div class="col-sm-10">
                            @if($Users->photo!="")
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div id="user_photo" class="col-sm-4 box p-a-xs">
                                            <a target="_blank"
                                               href="{{ route("fileView",["path" =>'users/'.$Users->photo ]) }}"><img
                                                    src="{{ route("fileView",["path" =>'users/'.$Users->photo ]) }}"
                                                    class="img-responsive">
                                                {{ $Users->photo }}
                                            </a>
                                            <br>
                                            <a onclick="document.getElementById('user_photo').style.display='none';document.getElementById('photo_delete').value='1';document.getElementById('undo').style.display='block';"
                                               class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                        </div>
                                        <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                            <a onclick="document.getElementById('user_photo').style.display='block';document.getElementById('photo_delete').value='0';document.getElementById('undo').style.display='none';">
                                                <i class="material-icons">&#xe166;</i> {!!  __('backend.undoDelete') !!}
                                            </a>
                                        </div>
                                        <input type="hidden" name="photo_delete" value="0" id="photo_delete">
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="photo" id="account_photo" accept="image/*" class="form-control"/>
                            <small>
                                <i class="material-icons">&#xe8fd;</i>
                                {!!  __('backend.imagesTypes') !!}
                            </small>
                        </div>
                    </div>

                    @if(@Auth::user()->permissionsGroup->webmaster_status)
                        <div class="form-group row">
                            <label for="permissions1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.Permission') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <select name="permissions_id" id="permissions_id" required
                                            class="form-control c-select">
                                        <option value="">- - {!!  __('backend.selectPermissionsType') !!} - -</option>
                                        @foreach ($Permissions as $Permission)
                                            <option value="{{ $Permission->id  }}" {!! ($Users->permissions_id==$Permission->id) ? "selected='selected'":"" !!}>{{ $Permission->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="status1"
                                   class="col-sm-2 form-control-label">{!!  __('backend.status') !!}</label>
                            <div class="col-sm-10">
                                <div class="radio">
                                    <label class="md-check">
                                        <input type="radio" name="status" value="1" class="has-value" {{ ($Users->status==1)?"checked":"" }} id="status1">
                                        <i class="primary"></i>
                                        {{ __('backend.active') }}
                                    </label>
                                    &nbsp; &nbsp;
                                    <label class="md-check">
                                        <input type="radio" name="status" value="0" class="has-value" {{ ($Users->status==0)?"checked":"" }} id="status2">
                                        <i class="danger"></i>
                                        {{ __('backend.notActive') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="permissions_id" value="{{ $Users->permissions_id }}">
                        <input type="hidden" name="status" value="{{ $Users->status }}">
                    @endif


                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                                    &#xe31b;</i> {!! __('backend.update') !!}</button>
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
