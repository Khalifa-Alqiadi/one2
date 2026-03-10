
<div class="tab-pane {{  ( Session::get('active_tab') == 'statusTab') ? 'active' : '' }}"
     id="tab-4">
    <div class="p-a-md"><h5><i class="material-icons">&#xe8c6;</i>
            &nbsp; {!!  __('backend.siteStatusSettings') !!}</h5></div>
    <div class="p-a-md col-md-12">
        <div class="form-group">
            <label for="site_status1">{{ __('backend.siteStatusSettings') }} : </label>
            <div class="radio">
                <label class="md-check">
                    <input type="radio" name="site_status" value="1" class="has-value" {{ ($Setting->site_status==1)?"checked":"" }} id="site_status1">
                    <i class="primary"></i>
                    {{ __('backend.active') }}
                </label>
                &nbsp; &nbsp;
                <label class="md-check">
                    <input type="radio" name="site_status" value="0" class="has-value" {{ ($Setting->site_status==0)?"checked":"" }} id="site_status2">
                    <i class="danger"></i>
                    {{ __('backend.notActive') }}
                </label>
            </div>
        </div>

        <div class="form-group"
             id="close_msg_div" {!!   ($Setting->site_status) ? "style='display:none'":"" !!}>
            <label for="close_msg">{!!  __('backend.siteStatusSettingsMsg') !!} </label>
            <div>
                @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                    <div>
                        <textarea name="close_msg" id="close_msg" class="form-control ckeditor">{{ $Setting->close_msg }}</textarea>
                    </div>
                @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                    <div>
                        <textarea name="close_msg" id="close_msg" class="form-control tinymce">{{ $Setting->close_msg }}</textarea>
                    </div>
                @else
                    <div class="box p-a-xs">
                        <textarea name="close_msg" id="close_msg" class="form-control summernote">{{ $Setting->close_msg }}</textarea>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
