
<div class="tab-pane {{  ( Session::get('active_tab') == 'googleTagsTab') ? 'active' : '' }}"
     id="tab-9">
    <div class="p-a-md"><h5>{!!  __('backend.googleTags') !!}</h5></div>


    <div class="p-a-md col-md-12">
        <div class="form-group">
            <label for="google_tags_status1">{{ __('backend.googleTagsStatus') }} : </label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="google_tags_status" value="0" class="has-value" {{ ($WebmasterSetting->google_tags_status ==0)?"checked":"" }} id="google_tags_status2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="google_tags_status" value="1" class="has-value" {{ ($WebmasterSetting->google_tags_status ==1)?"checked":"" }} id="google_tags_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>

        <div
            id="google_tags_div" {!!  ( !$WebmasterSetting->google_tags_status) ? "style='display:none'":"" !!}>

            <div class="form-group">
                <label for="google_tags_id">{!!  __('backend.googleTagsContainerId') !!}</label>
                <input type="text" autocomplete="off" name="google_tags_id" id="google_tags_id" value="{{ $WebmasterSetting->google_tags_id }}" placeholder="" class="form-control" dir="ltr"/>
            </div>

        </div>
        <a href="https://www.google.com/analytics/tag-manager/"
           style="text-decoration: underline" target="_blank"><small><i
                    class="material-icons">&#xe8fd;</i> Google Tag Manager</small></a>

    </div>
</div>
