
<div class="tab-pane {{  ( Session::get('active_tab') == 'restfulAPITab') ? 'active' : '' }}"
     id="tab-6">
    <div class="p-a-md"><h5>{!!  __('backend.restfulAPI') !!}</h5></div>

    <div class="p-a-md col-md-12">

        <div class="form-group">
            <label for="api_status1">{{ __('backend.APIStatus') }} : </label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="api_status" value="0" class="has-value" {{ ($WebmasterSetting->api_status ==0)?"checked":"" }} id="api_status2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="api_status" value="1" class="has-value" {{ ($WebmasterSetting->api_status ==1)?"checked":"" }} id="api_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group" id="api_key_div"
             style="display: {{ ($WebmasterSetting->api_status==1)?"block":"none" }}">
            <label for="api_url">{!!  __('backend.apiURL') !!} : </label>
            <input type="text" autocomplete="off" name="api_url" id="api_url" value="{{ route("apiURL") }}" placeholder="" class="form-control" readonly dir="ltr"/>
            <br>
            <label for="api_key">{!!  __('backend.APIKey') !!} : </label>
            <input type="text" autocomplete="off" name="api_key" id="api_key" value="{{ $WebmasterSetting->api_key }}" placeholder="" class="form-control" readonly dir="ltr"/>
            <a href="javascript:void(0)" onclick="generate_key()">
                <small>{!!  __('backend.APIKeyGenerate') !!}</small>
            </a>
            <br>
            <br>

            <div>
                <a href="https://smartend.app/api-docs"
                   class="btn white dk w-full" target="_blank"><i class="fa fa-support"></i> {!!  __('backend.apiDocs') !!}</a>
            </div>

        </div>
    </div>
</div>
