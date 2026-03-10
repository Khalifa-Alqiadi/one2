
<div class="tab-pane {{  ( Session::get('active_tab') == 'registrationSettingsTab') ? 'active' : '' }}"
     id="tab-4">
    <div class="p-a-md"><h5>{!!  __('backend.registrationSettings') !!}</h5></div>

    <div class="p-a-md col-md-12">


        <div class="form-group">
            <label for="backend_path">{{ __('backend.controlPanelPath') }} : </label>
            <div class="pull-right text-muted" dir="ltr">
                {{ config('app.url') }}/
            </div>
            <input type="text" name="backend_path" id="backend_path" value="{{ config('smartend.backend_path') }}" placeholder="{{ config('app.url').'/admin' }}" class="form-control backend_path" dir="ltr"/>
        </div>
        <div class="form-group">
            <label>{{ __('backend.permissionForNewUsers') }} : </label>
            <select name="permission_group" id="permission_group" class="form-control c-select">
                @foreach ($PermissionsGroups as $PermissionsGroup)
                    <?php
                    ?>
                    <option
                        value="{{ $PermissionsGroup->id  }}" {{ ($PermissionsGroup->id == $WebmasterSetting->permission_group) ? "selected='selected'":""  }}>{!! $PermissionsGroup->name   !!}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group m-t-3">
            <label for="register_status1"><h6>{{ __('backend.allowRegister') }}</h6></label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="register_status" value="0" class="has-value" {{ ($WebmasterSetting->register_status ==0)?"checked":"" }} id="register_status2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="register_status" value="1" class="has-value" {{ ($WebmasterSetting->register_status ==1)?"checked":"" }} id="register_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="row m-t-3">
            <div class="form-group col-md-4">
                <label class="login_facebook_status1"><h6>{{ __('backend.loginWithFacebook') }}
                        <a target="_blank"
                           href="https://developers.facebook.com/apps">
                            <i class="material-icons">&#xe8fd;</i>
                        </a>
                    </h6></label>
                <div class="radio">
                    <div>
                        <label class="md-check">
                            <input type="radio" name="login_facebook_status" value="0" class="has-value" {{ ($WebmasterSetting->login_facebook_status ==0)?"checked":"" }} id="login_facebook_status2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                    <div style="margin-top: 5px;">
                        <label class="md-check">
                            <input type="radio" name="login_facebook_status" value="1" class="has-value" {{ ($WebmasterSetting->login_facebook_status ==1)?"checked":"" }} id="login_facebook_status1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="facebook_ids_div"
                 style="display: {{ ($WebmasterSetting->login_facebook_status==1)?"block":"none" }}">
                <div class="form-group row">
                    <label for="login_facebook_client_id" class="col-sm-2 form-control-label">{!!  __('backend.loginAppID') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_facebook_client_id" id="login_facebook_client_id" value="{{ $WebmasterSetting->login_facebook_client_id }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="login_facebook_client_secret" class="col-sm-2 form-control-label">{!!  __('backend.loginAppSecret') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_facebook_client_secret" id="login_facebook_client_secret" value="{{ $WebmasterSetting->login_facebook_client_secret }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" for="login_facebook_callbackURL">
                        <small>{!!  __('backend.callbackURL') !!}</small>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_facebook_callbackURL" id="login_facebook_callbackURL" value="{{ config('app.url') . '/oauth/facebook/callback' }}" dir="ltr" class="form-control" style="font-size:12px" readonly/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-2">
            <div class="form-group col-md-4">
                <label class="login_twitter_status1"><h6>{{ __('backend.loginWithTwitter') }}
                        <a target="_blank"
                           href="https://apps.twitter.com">
                            <i class="material-icons">&#xe8fd;</i>
                        </a></h6></label>
                <div class="radio">
                    <div>
                        <label class="md-check">
                            <input type="radio" name="login_twitter_status" value="0" class="has-value" {{ ($WebmasterSetting->login_twitter_status ==0)?"checked":"" }} id="login_twitter_status2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                    <div style="margin-top: 5px;">
                        <label class="md-check">
                            <input type="radio" name="login_twitter_status" value="1" class="has-value" {{ ($WebmasterSetting->login_twitter_status ==1)?"checked":"" }} id="login_twitter_status1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="twitter_ids_div"
                 style="display: {{ ($WebmasterSetting->login_twitter_status==1)?"block":"none" }}">
                <div class="form-group row">
                    <label for="login_twitter_client_id" class="col-sm-2 form-control-label">{!!  __('backend.loginConsumerAppKey') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_twitter_client_id" id="login_twitter_client_id" value="{{ $WebmasterSetting->login_twitter_client_id }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="login_twitter_client_secret" class="col-sm-2 form-control-label">{!!  __('backend.loginConsumerAppSecret') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_twitter_client_secret" id="login_twitter_client_secret" value="{{ $WebmasterSetting->login_twitter_client_secret }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" for="login_twitter_callbackURL">
                        <small>{!!  __('backend.callbackURL') !!}</small>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_twitter_callbackURL" id="login_twitter_callbackURL" value="{{ config('app.url') . '/oauth/twitter/callback' }}" dir="ltr" class="form-control" style="font-size:12px" readonly/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-2">
            <div class="form-group col-md-4">
                <label for="login_google_status1"><h6>{{ __('backend.loginWithGoogle') }}
                        <a target="_blank"
                           href="https://developers.google.com/identity/sign-in/web/sign-in">
                            <i class="material-icons">&#xe8fd;</i>
                        </a></h6></label>
                <div class="radio">
                    <div>
                        <label class="md-check">
                            <input type="radio" name="login_google_status" value="0" class="has-value" {{ ($WebmasterSetting->login_google_status ==0)?"checked":"" }} id="login_google_status2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                    <div style="margin-top: 5px;">
                        <label class="md-check">
                            <input type="radio" name="login_google_status" value="1" class="has-value" {{ ($WebmasterSetting->login_google_status ==1)?"checked":"" }} id="login_google_status1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="google_ids_div"
                 style="display: {{ ($WebmasterSetting->login_google_status==1)?"block":"none" }}">
                <div class="form-group row">
                    <label for="login_google_client_id" class="col-sm-2 form-control-label">{!!  __('backend.loginClientID') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_google_client_id" id="login_google_client_id" value="{{ $WebmasterSetting->login_google_client_id }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="login_google_client_secret" class="col-sm-2 form-control-label">{!!  __('backend.loginClientSecret') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_google_client_secret" id="login_google_client_secret" value="{{ $WebmasterSetting->login_google_client_secret }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" for="login_google_callbackURL">
                        <small>{!!  __('backend.callbackURL') !!}</small>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_google_callbackURL" id="login_google_callbackURL" value="{{ config('app.url') . '/oauth/google/callback' }}" dir="ltr" class="form-control" style="font-size:12px" readonly/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-2">
            <div class="form-group col-md-4">
                <label class="login_linkedin_status1"><h6>{{ __('backend.loginWithLinkedIn') }}
                        <a target="_blank"
                           href="https://www.linkedin.com/developer/apps/">
                            <i class="material-icons">&#xe8fd;</i>
                        </a></h6></label>
                <div class="radio">
                    <div>
                        <label class="md-check">
                            <input type="radio" name="login_linkedin_status" value="0" class="has-value" {{ ($WebmasterSetting->login_linkedin_status ==0)?"checked":"" }} id="login_linkedin_status2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                    <div style="margin-top: 5px;">
                        <label class="md-check">
                            <input type="radio" name="login_linkedin_status" value="1" class="has-value" {{ ($WebmasterSetting->login_linkedin_status ==1)?"checked":"" }} id="login_linkedin_status1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="linkedin_ids_div"
                 style="display: {{ ($WebmasterSetting->login_linkedin_status==1)?"block":"none" }}">
                <div class="form-group row">
                    <label for="login_linkedin_client_id" class="col-sm-2 form-control-label">{!!  __('backend.loginClientID') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_linkedin_client_id" id="login_linkedin_client_id" value="{{ $WebmasterSetting->login_linkedin_client_id }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="login_linkedin_client_secret" class="col-sm-2 form-control-label">{!!  __('backend.loginClientSecret') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_linkedin_client_secret" id="login_linkedin_client_secret" value="{{ $WebmasterSetting->login_linkedin_client_secret }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" for="login_linkedin_callbackURL">
                        <small>{!!  __('backend.callbackURL') !!}</small>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_linkedin_callbackURL" id="login_linkedin_callbackURL" value="{{ config('app.url') . '/oauth/linkedin/callback' }}" dir="ltr" class="form-control" style="font-size:12px" readonly/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-2">
            <div class="form-group col-md-4">
                <label for="login_github_status1"><h6>{{ __('backend.loginWithGitHub') }}
                        <a target="_blank"
                           href="https://github.com/settings/developers">
                            <i class="material-icons">&#xe8fd;</i>
                        </a></h6></label>
                <div class="radio">
                    <div>
                        <label class="md-check">
                            <input type="radio" name="login_github_status" value="0" class="has-value" {{ ($WebmasterSetting->login_github_status ==0)?"checked":"" }} id="login_github_status2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                    <div style="margin-top: 5px;">
                        <label class="md-check">
                            <input type="radio" name="login_github_status" value="1" class="has-value" {{ ($WebmasterSetting->login_github_status ==1)?"checked":"" }} id="login_github_status1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="github_ids_div"
                 style="display: {{ ($WebmasterSetting->login_github_status==1)?"block":"none" }}">
                <div class="form-group row">
                    <label for="login_github_client_id" class="col-sm-2 form-control-label">{!!  __('backend.loginClientID') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_github_client_id" id="login_github_client_id" value="{{ $WebmasterSetting->login_github_client_id }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="login_github_client_secret" class="col-sm-2 form-control-label">{!!  __('backend.loginClientSecret') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_github_client_secret" id="login_github_client_secret" value="{{ $WebmasterSetting->login_github_client_secret }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" for="login_github_callbackURL">
                        <small>{!!  __('backend.callbackURL') !!}</small>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_github_callbackURL" id="login_github_callbackURL" value="{{ config('app.url') . '/oauth/github/callback' }}" dir="ltr" class="form-control" style="font-size:12px" readonly/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-2">
            <div class="form-group col-md-4">
                <label for="login_bitbucket_status1"><h6>{{ __('backend.loginWithBitbucket') }}
                        <a target="_blank"
                           href="https://bitbucket.org/account">
                            <i class="material-icons">&#xe8fd;</i>
                        </a></h6></label>
                <div class="radio">
                    <div>
                        <label class="md-check">
                            <input type="radio" name="login_bitbucket_status" value="0" class="has-value" {{ ($WebmasterSetting->login_bitbucket_status ==0)?"checked":"" }} id="login_bitbucket_status2">
                            <i class="danger"></i>
                            {{ __('backend.notActive') }}
                        </label>
                    </div>
                    <div style="margin-top: 5px;">
                        <label class="md-check">
                            <input type="radio" name="login_bitbucket_status" value="1" class="has-value" {{ ($WebmasterSetting->login_bitbucket_status ==1)?"checked":"" }} id="login_bitbucket_status1">
                            <i class="primary"></i>
                            {{ __('backend.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-8" id="bitbucket_ids_div"
                 style="display: {{ ($WebmasterSetting->login_bitbucket_status==1)?"block":"none" }}">
                <div class="form-group row">
                    <label for="login_bitbucket_client_id" class="col-sm-2 form-control-label">{!!  __('backend.loginKey') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_bitbucket_client_id" id="login_bitbucket_client_id" value="{{ $WebmasterSetting->login_bitbucket_client_id }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="login_bitbucket_client_secret" class="col-sm-2 form-control-label">{!!  __('backend.loginSecret') !!}</label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_bitbucket_client_secret" id="login_bitbucket_client_secret" value="{{ $WebmasterSetting->login_bitbucket_client_secret }}" dir="ltr" class="form-control"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 form-control-label" for="login_bitbucket_callbackURL">
                        <small>{!!  __('backend.callbackURL') !!}</small>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="login_bitbucket_callbackURL" id="login_bitbucket_callbackURL" value="{{ config('app.url') . '/oauth/bitbucket/callback' }}" dir="ltr" class="form-control" style="font-size:12px" readonly/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
