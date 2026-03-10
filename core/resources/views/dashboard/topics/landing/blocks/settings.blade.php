<div class="col-sm-3">
    <div class="{{ (@Helper::currentLanguage()->direction=="rtl")?"p-r-2":"p-l-2"  }}">
        <div class="form-group row">
            <label for="tag_title" class="col-sm-12 form-control-label">{!!  __('backend.blockName') !!} <span class="text-danger">*</span>
            </label>
            <div class="col-sm-12">
                <input type="text" autocomplete="off" name="block_name" id="block_name" value="{{ @$TopicBlock->block_name }}" required maxlength="191" placeholder="" class="form-control"/>
            </div>
        </div>
        <div class="m-b-sm">
            <label class="md-switch">
                <input type="checkbox" name="title_status" id="title_status" value="1" {{ (@$TopicBlock->title_status)?"checked":"" }} class="has-value">
                <i class="primary"></i>
                {!!  __('backend.blockTitleStatus') !!}
            </label>
        </div>
        <div class="m-b-sm">
            <label class="md-switch">
                <input type="checkbox" name="desc_status" id="desc_status" value="1" {{ (@$TopicBlock->desc_status)?"checked":"" }} class="has-value">
                <i class="primary"></i>
            {!!  __('backend.blockDescStatus') !!}
        </div>
        <div class="m-b-sm">
            <label class="md-switch">
                <input type="checkbox" name="image_status" id="image_status" value="1" {{ (@$TopicBlock->image_status)?"checked":"" }} class="has-value">
                <i class="primary"></i>
            {!!  __('backend.blockImageStatus') !!}
        </div>
        <div class="m-b-sm">
            <label class="md-switch">
                <input type="checkbox" name="divider_status" id="divider_status" value="1" {{ (@$TopicBlock->divider_status)?"checked":"" }} class="has-value">
                <i class="primary"></i>
                {!!  __('backend.blockDividerStatus') !!}
            </label>
        </div>
        <div class="m-b-sm displayNone" id="more_btn_status_settings">
            <label class="md-switch">
                <input type="checkbox" name="more_btn_status" id="more_btn_status" value="1" {{ (@$TopicBlock->more_btn_status)?"checked":"" }} class="has-value">
                <i class="primary"></i>
                {!!  __('backend.viewMoreButton') !!}
            </label>
        </div>
        <div class="form-group row">
            <label for="tag_title" class="col-sm-12 form-control-label">{!!  __('backend.blockBGColor') !!}</label>
            <div class="col-sm-12">
                <div id="cp1" class="input-group colorpicker-component">
                    <input type="text" autocomplete="off" name="bg_color" id="bg_color" value="{{ (@$TopicBlock->bg_color !="")?@$TopicBlock->bg_color:"" }}" dir="ltr" class="form-control"/>
                    <span class="input-group-addon" id="cpbg"><i></i></span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="tag_title" class="col-sm-12 form-control-label">CSS Classes</label>
            <div class="col-sm-12">
                <input type="text" autocomplete="off" name="css_classes" id="css_classes" value="{{ @$TopicBlock->css_classes }}" dir="ltr" class="form-control"/>
            </div>
        </div>
        <label class="md-switch m-b-sm">
            <input type="checkbox" name="status" value="1" {{ (@$TopicBlock->status || empty(@$TopicBlock))?"checked":"" }} class="has-value">
            <i class="primary"></i>
            {!!  __('backend.blockStatus') !!}
        </label>
    </div>
</div>
<link rel="stylesheet"
      href="{{ asset('assets/dashboard/js/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}"
      type="text/css"/>
<script src="{{ asset('assets/dashboard/js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script>
    $(function () {
        let colors = {
            'black': '#000000',
            'white': '#ffffff',
            'red': '#FF0000',
            'default': '#777777',
            'primary': '#337ab7',
            'success': '#5cb85c',
            'info': '#5bc0de',
            'warning': '#f0ad4e',
            'danger': '#d9534f'
        };
        $('#cp1').colorpicker({
            colorSelectors: colors
        });

        $('#title_status').change(function () {
            $('#field_of_title').toggle(this.checked);
        });
        $('#desc_status').change(function () {
            $('#field_of_desc').toggle(this.checked);
        });
        $('#image_status').change(function () {
            $('#field_of_image').toggle(this.checked);
        });
    });
</script>
