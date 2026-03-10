@extends('dashboard.layouts.auth')
@section('title', "403")
@section('content')
    <div class="app-body amber bg-auto" style="height: 100vh">
        <div class="text-center pos-rlt p-y-md">
            <h2 class="h1 m-y-lg text-black">{{ __('backend.oops') }}!</h2>
            <p class="h5 m-y-lg font-bold text-black">{{ __('backend.noPermission') }}.</p>
            <a href="{{ route("adminHome") }}" class="md-btn amber-700 md-raised p-x-md">
                <span class="text-white">{{ __('backend.returnTo') }} <i class="material-icons">&#xe5c4;</i></span>
            </a>
        </div>
    </div>
@endsection

