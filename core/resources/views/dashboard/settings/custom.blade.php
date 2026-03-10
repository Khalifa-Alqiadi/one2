<div class="tab-pane {{  ( Session::get('active_tab') == 'codeTab') ? 'active' : '' }}"
     id="tab-7">
    <div class="p-a-md"><h5><i class="material-icons">&#xe86f;</i>
            &nbsp; {!!  __('backend.customCode') !!}</h5></div>
    <div class="p-a-md col-md-12">
        <div class="form-group">
            <h6>{!!  __('backend.customCSS') !!}</h6>
            <div dir="ltr">
                <textarea name="css_code" id="css_code" class="form-control" dir="ltr" rows="15">{{ $Setting->css }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <h6>{!!  __('backend.customCodeOnHead') !!}</h6>
            <div dir="ltr">
            <textarea name="js_code" class="form-control" rows="10" dir="ltr" id="js_code" placeholder="<style>
...
</style>

<script>
...
</script>">{{ $Setting->js }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <h6>{!!  __('backend.customCodeOnBody') !!}</h6>
            <div dir="ltr">
                <textarea name="body_code" id="body_code" class="form-control" dir="ltr" rows="10">{{ $Setting->body }}</textarea>
            </div>
        </div>
    </div>
</div>
