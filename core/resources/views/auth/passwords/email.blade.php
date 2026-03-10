@extends('dashboard.layouts.auth')
@section('title', __('backend.forgotPassword'))
@section('content')
    @if(config('smartend.mail_driver') != "" && config('smartend.mail_username') !="" && config('smartend.mail_password'))
        <div class="center-block w-xxl p-a-2">
            <div class="p-a-md box-color r box-shadow-z4 text-color">
                <div class="text-center">
                    @if(Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code) !="")
                        <img alt="" class="app-logo"
                             src="{{ route("fileView",["path" =>'settings/'.Helper::GeneralSiteSettings("style_logo_" . @Helper::currentLanguage()->code) ]) }}">
                    @else
                        <img alt="" src="{{ route("fileView",["path" =>'settings/nologo.png' ]) }}">
                    @endif
                </div>
                <div class="m-y text-muted text-center">
                    {{ __('backend.forgotPassword') }}
                </div>
                <div class="text-muted text-left">
                    <p class="text-xs m-t">{{ __('backend.enterYourEmail') }}</p>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form name="reset" method="POST"
                      action="{{ url('/'.config('smartend.backend_path').'/password/email') }}">
                    {{ csrf_field() }}
                    <div class="md-form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" name="email" value="{{ old('email') }}" class="md-input" required>
                        <label>{{ __('backend.yourEmail') }}</label>
                    </div>
                    @if ($errors->has('email'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <button type="submit"
                            class="btn primary btn-block p-x-md">{{ __('backend.sendPasswordResetLink') }}</button>
                </form>

                <p id="alerts-container"></p>
                <div class="p-v-lg text-center">{{ __('backend.returnTo') }} <a
                        href="{{ url('/'.config('smartend.backend_path').'/login') }}"
                        class="text-primary _600">{{ __('backend.signIn') }}</a>
                </div>
            </div>
        </div>
    @endif
@endsection

