<?php
$Adviser = Auth::user();
$title_var = "title_" . __('backend.boxCode');
$title_var2 = "title_" . __('backend.boxCodeOther');
?>


@foreach(Helper::languagesList() as $ActiveLanguage)
    @if($ActiveLanguage->box_status)
        <div class="form-group row">
            <label for="edit_btn_title_{{ @$ActiveLanguage->code }}"
                class="col-sm-3 form-control-label">{!!  __('backend.topicName') !!} {!! @Helper::languageName($ActiveLanguage) !!}
            </label>
            <div class="col-sm-9">
                <input type="text" autocomplete="off" name="btn_title_{{ @$ActiveLanguage->code }}" id="edit_btn_title_{{ @$ActiveLanguage->code }}" value="{{ @$home_page_button['btn_title_'.@$ActiveLanguage->code] }}" required maxlength="100" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
            </div>
        </div>
    @endif
@endforeach
<div class="form-group row">
    <label for="edit_btn_link" class="col-sm-3 form-control-label">{!!  __('backend.link') !!}
    </label>
    <div class="col-sm-9">
        <input type="text" autocomplete="off" name="link" id="edit_btn_link" value="{{ @$home_page_button['btn_link'] }}" required dir="ltr" class="form-control"/>
    </div>
</div>

<div class="form-group row">
    <label for="edit_target0"
           class="col-sm-3 form-control-label">{!!  __('backend.linkTarget') !!}</label>
    <div class="col-sm-9">
        <div class="radio m-t-sm">
            <label class="md-check m-b-sm">
                <input type="radio" name="target" id="edit_target0" value="0" class="has-value" {{ (@$home_page_button['btn_target'])?"":"checked" }}>
                <i class="primary"></i>
                {{ __('backend.linkTargetParent') }}
            </label>
            &nbsp; &nbsp;
            <label class="md-check m-b-sm">
                <input type="radio" name="target" id="edit_target1" value="1" class="has-value" {{ (@$home_page_button['btn_target'])?"checked":"" }}>
                <i class="danger"></i>
                {{ __('backend.linkTargetBlank') }}
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label for="edit_btn_class" class="col-sm-3 form-control-label"> CSS Class
    </label>
    <div class="col-sm-9">
        <input type="text" autocomplete="off" name="btn_class" id="edit_btn_class" value="{{ @$home_page_button['btn_class'] }}"
               dir="ltr" class="form-control" required/>
    </div>
</div>
<input type="hidden" name="id" value="{{ @$home_page_button['btn_id'] }}">
<input type="hidden" name="p_id" value="{{ $Permissions->id }}">
