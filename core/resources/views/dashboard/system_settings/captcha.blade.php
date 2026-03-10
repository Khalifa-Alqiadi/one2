
<div class="tab-pane {{  ( Session::get('active_tab') == 'googleRecaptchaTab') ? 'active' : '' }}"
     id="tab-8">
    <div class="p-a-md"><h5>{!!  __('backend.googleRecaptcha') !!}</h5></div>

    <div class="p-a-md col-md-12">
        <div class="form-group">
            <label class="nocaptcha_status1">{{ __('backend.googleRecaptchaStatus') }} : </label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="nocaptcha_status" value="0" class="has-value" {{ ($WebmasterSetting->nocaptcha_status ==0)?"checked":"" }} id="nocaptcha_status2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="nocaptcha_status" value="1" class="has-value" {{ ($WebmasterSetting->nocaptcha_status ==1)?"checked":"" }} id="nocaptcha_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>

        <div
            id="nocaptcha_div" {!!  ( !$WebmasterSetting->nocaptcha_status) ? "style='display:none'":"" !!}>

            <div class="form-group">
                <label for="nocaptcha_sitekey">{!!  __('backend.googleRecaptchaSitekey') !!}</label>
                <input type="text" autocomplete="off" name="nocaptcha_sitekey" id="nocaptcha_sitekey" value="{{ $WebmasterSetting->nocaptcha_sitekey }}" placeholder="" class="form-control" dir="ltr"/>
            </div>

            <div class="form-group">
                <label for="nocaptcha_secret">{!!  __('backend.googleRecaptchaSecret') !!}</label>
                <input type="text" autocomplete="off" name="nocaptcha_secret" id="nocaptcha_secret" value="{{ $WebmasterSetting->nocaptcha_secret }}" placeholder="" class="form-control" dir="ltr"/>
            </div>
        </div>
        <a href="https://www.google.com/recaptcha"
           style="text-decoration: underline" target="_blank"><small><i
                    class="material-icons">&#xe8fd;</i> Google reCAPTCHA</small></a>
    </div>
</div>
