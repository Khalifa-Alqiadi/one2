<div class="tab-pane {{  ( Session::get('active_tab') == 'googleMapsTab') ? 'active' : '' }}"
     id="tab-10">
    <div class="p-a-md"><h5>{!!  __('backend.googleMaps') !!}</h5></div>


    <div class="p-a-md col-md-12">
        <div class="form-group">
            <label class="google_maps_status1">{{ __('backend.googleMapsStatus') }} : </label>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="google_maps_status" value="0" class="has-value" {{ (config('smartend.google_maps_key') =="")?"checked":"" }} id="google_maps_status2">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="google_maps_status" value="1" class="has-value" {{ (config('smartend.google_maps_key') !="")?"checked":"" }} id="google_maps_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>

        <div
            id="google_maps_div" {!!  ( config('smartend.google_maps_key') =="") ? "style='display:none'":"" !!}>

            <div class="form-group">
                <label for="google_maps_key">{!!  __('backend.googleMapsKey') !!}</label>
                <input type="text" autocomplete="off" name="google_maps_key" id="google_maps_key" value="{{ config('smartend.google_maps_key') }}" placeholder="" class="form-control" dir="ltr"/>
            </div>

        </div>

        <a href="https://developers.google.com/maps/documentation/javascript/get-api-key"
           style="text-decoration: underline" target="_blank">
            <small><i
                    class="material-icons">&#xe8fd;</i> Google Maps</small>
        </a>

    </div>
</div>
