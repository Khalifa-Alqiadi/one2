@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.environment.title'))
@section('style')
    <link href="{{ asset('assets/installer/froiden-helper/helper.css') }}" rel="stylesheet"/>
    <style>
        .form-control{
            width: 100%;
        }
        .has-error{
            color: #c95757;
        }
        .has-error input{
            color: black;
            border:1px solid #c95757;
        }
        .alert{
            background: #c95757;
        }
    </style>
@endsection
@section('container')
    <form method="post" action="{{ route('LaravelInstaller::environmentSave') }}" id="env-form">
        <div class="form-group">
            <label class="col-sm-2 control-label">Database Connection</label>

            <div class="col-sm-10">
                <select name="connection" class="form-control">
                    <option value="mysql">mysql</option>
                    <option value="pgsql">pgsql</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Database Host</label>

            <div class="col-sm-10">
                <input type="text" name="hostname" class="form-control" value="127.0.0.1">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Database Port</label>

            <div class="col-sm-10">
                <input type="text" name="port" class="form-control" value="3306">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Database Name</label>
            <div class="col-sm-10">
                <input type="text" name="database" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Database Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label  class="col-sm-2 control-label">Database Password</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="password">
            </div>
        </div>
        <div class="modal-footer">
            <div class="buttons">
                <button class="button" onclick="checkEnv();return false">
                    {{ trans('installer_messages.environment.next') }}
                </button>
            </div>
        </div>
    </form>
    <script>
        function checkEnv() {
            $.easyAjax({
                url: "{!! route('LaravelInstaller::environmentSave') !!}",
                type: "GET",
                data: $("#env-form").serialize(),
                container: "#env-form",
                messagePosition: "inline"
            });
        }
    </script>
@stop
@section('scripts')
    <script src="{{ asset('assets/installer/js/jQuery-2.2.0.min.js') }}"></script>
    <script src="{{ asset('assets/installer/froiden-helper/helper.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection
