<div class="tab-pane {{  ( Session::get('active_tab') == 'debugMode') ? 'active' : '' }}"
     id="tab-14">
    <div class="p-a-md"><h5>{!!  __('backend.debugMode') !!}</h5></div>

    <div class="p-a-md col-md-12">
        <div class="form-group">
            <label for="debug_mode_status1">{{ __('backend.debugModeWarn') }} </label>
            <hr>
            <div class="radio">
                <div>
                    <label class="md-check">
                        <input type="radio" name="debug_mode_status" value="0" class="has-value" {{ (config('smartend.app_debug') =="")?"checked":"" }} id="debug_mode_status0">
                        <i class="danger"></i>
                        {{ __('backend.notActive') }}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check">
                        <input type="radio" name="debug_mode_status" value="1" class="has-value" {{ (config('smartend.app_debug') !="")?"checked":"" }} id="debug_mode_status1">
                        <i class="primary"></i>
                        {{ __('backend.active') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
