<div class="tab-pane {{  ( Session::get('active_tab') == 'newsletterTab') ? 'active' : '' }}"
     id="tab-15">
    <div class="p-a-md"><h5>{!!  __('backend.newsletterProvider') !!}</h5></div>

    <div class="p-a-md col-md-12">

        <div class="form-group">
            <label for="newsletter_provider_status1">{{ __('backend.newsletterStatus') }} : </label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="newsletter_provider_status" value="0" class="has-value" {{ (config('smartend.newsletter_status') ==0)?"checked":"" }} id="newsletter_provider_status0">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="newsletter_provider_status" value="1" class="has-value" {{ (config('smartend.newsletter_status') ==1)?"checked":"" }} id="newsletter_provider_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>
        <div id="newsletter_service_info" class="{{ (config('smartend.newsletter_status') ==1)?"":"displayNone" }}">
            <div class="form-group">
                <label for="newsletter_provider">{!!  __('backend.newsletterProvider') !!}</label>
                <select name="newsletter_provider" id="newsletter_provider" class="form-control c-select">
                    <option
                        value="mailchimp" {{ (config('smartend.newsletter_provider') == "mailchimp") ? "selected='selected'":""  }}>
                        mailchimp.com ( Default )
                    </option>
                    <option
                        value="mailcoach" {{ (config('smartend.newsletter_provider') == "mailcoach") ? "selected='selected'":""  }}>
                        mailcoach.app
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="newsletter_api_key">API Key</label>
                <input type="text" autocomplete="off" name="newsletter_api_key" id="newsletter_api_key" value="{{ config('smartend.newsletter_api_key') }}" placeholder="" class="form-control" dir="ltr"/>
            </div>
            <div class="form-group {{ (config('smartend.newsletter_provider') =="mailcoach")?"":"displayNone" }}" id="newsletter_endpoint_div">
                <label for="newsletter_endpoint">End Point</label>
                <input type="text" autocomplete="off" name="newsletter_endpoint" id="newsletter_endpoint" value="{{ config('smartend.newsletter_endpoint') }}" placeholder="" class="form-control" dir="ltr"/>
            </div>
            <div class="form-group">
                <label for="newsletter_list_id">List ID</label>
                <input type="text" autocomplete="off" name="newsletter_list_id" id="newsletter_list_id" value="{{ config('smartend.newsletter_list_id') }}" placeholder="" class="form-control" dir="ltr"/>
            </div>
            <div class="form-group">
                <label>{!!  __('backend.analyticsApiMsg') !!} :</label>
                <div>
                    <a href="https://mailchimp.com/" class="btn rounded btn-outline b-info text-info" target="_blank"><i class="material-icons">&#xe895;</i>
                        mailchimp.com</a>
                    <a href="https://mailcoach.app/" class="btn rounded btn-outline b-info text-info" target="_blank"><i class="material-icons">&#xe895;</i>
                        mailcoach.app</a>
                </div>
            </div>
        </div>
    </div>
</div>
