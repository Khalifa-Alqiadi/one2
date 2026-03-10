<div class="tab-pane {{  ( Session::get('active_tab') == 'analyticsTab') ? 'active' : '' }}"
     id="tab-11">
    <div class="p-a-md"><h5>{!!  __('backend.analyticsSettings') !!}</h5></div>

    <div class="p-a-md col-md-12">

        <div class="form-group">
            <label class="sms_status1">{{ __('backend.analyticsService') }} : </label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="geoip_status" value="0" class="has-value" {{ (config('smartend.geoip_status') ==0)?"checked":"" }} id="sms_status2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="geoip_status" value="1" class="has-value" {{ (config('smartend.geoip_status') ==1)?"checked":"" }} id="sms_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>
        <div id="geoip_service_info" class="{{ (config('smartend.geoip_status') ==1)?"":"displayNone" }}">
            <div class="form-group">
                <label>{!!  __('backend.analyticsService') !!}</label>
                <select name="geoip_service" class="form-control c-select">
                    <option value="ipapi" {{ (config('smartend.geoip_service')== "ipapi") ? "selected='selected'":""  }}>
                        {!!  __('backend.usingService') !!} : ip-api.com ( Default )
                    </option>
                    <option value="ipapico" {{ (config('smartend.geoip_service')== "ipapico") ? "selected='selected'":""  }}>
                        {!!  __('backend.usingService') !!} : ipapi.co
                    </option>
                    <option
                        value="ipgeolocation" {{ (config('smartend.geoip_service')== "ipgeolocation") ? "selected='selected'":""  }}>
                        {!!  __('backend.usingService') !!} : ipgeolocation.io
                    </option>
                    <option value="ipfinder" {{ (config('smartend.geoip_service')== "ipfinder") ? "selected='selected'":""  }}>
                        {!!  __('backend.usingService') !!} : ipfinder.io
                    </option>
                    <option value="ipdata" {{ (config('smartend.geoip_service')== "ipdata") ? "selected='selected'":""  }}>
                        {!!  __('backend.usingService') !!} : ipdata.co
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="geoip_service_key">{!!  __('backend.analyticsApiKey') !!}</label>
                <input type="text" autocomplete="off" name="geoip_service_key" id="geoip_service_key" value="{{ config('smartend.geoip_service_key') }}" placeholder="" class="form-control" dir="ltr"/>
            </div>
            <div class="form-group">
                <label>{!!  __('backend.analyticsApiMsg') !!} :</label>
                <div>
                    <a href="https://ip-api.com/" class="btn rounded btn-outline b-info text-info" target="_blank"><i class="material-icons">&#xe895;</i>
                        ip-api.com</a>
                    <a href="https://ipapi.co/" class="btn rounded btn-outline b-info text-info" target="_blank"><i class="material-icons">&#xe895;</i>
                        ipapi.co</a>
                    <a href="https://ipgeolocation.io/" class="btn rounded btn-outline b-info text-info" target="_blank"><i class="material-icons">&#xe895;</i>
                        ipgeolocation.io</a>
                    <a href="http://ipfinder.io/" class="btn rounded btn-outline b-info text-info" target="_blank"><i class="material-icons">&#xe895;</i>
                        ipfinder.io</a>
                    <a href="https://ipdata.co/" class="btn rounded btn-outline b-info text-info" target="_blank"><i class="material-icons">&#xe895;</i>
                        ipdata.co</a>
                </div>
            </div>
        </div>
    </div>
</div>
