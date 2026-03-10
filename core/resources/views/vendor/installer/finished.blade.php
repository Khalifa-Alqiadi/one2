@extends('vendor.installer.layouts.master')

@section('title', trans('installer_messages.final.title'))
@section('container')
    <p class="paragraph" style="margin-bottom:10px;color: #73ba00">{{ session('message')['message'] }}</p>
    <strong>Default Administrator User:</strong>
    <div style="padding: 10px 20px;border: 1px dashed red">
        Email : <strong style="color: red">admin@site.com</strong><br>
        Password: <strong style="color: red">admin</strong>
    </div>
    <br>
    <div class="buttons">
        <a href="{{ route('adminHome') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>
@stop
