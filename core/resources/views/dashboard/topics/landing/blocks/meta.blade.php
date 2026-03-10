<div id="field_of_title" class="{{ (@$TopicBlock->title_status)?"":"displayNone" }}">
    @foreach(Helper::languagesList() as $ActiveLanguage)
        @if($ActiveLanguage->box_status)
            <div class="form-group row">
                <label for="title_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.blockTitle') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                </label>
                <div class="col-sm-9">
                    <input type="text" autocomplete="off" name="title_{{ @$ActiveLanguage->code }}" value="{{ @$TopicBlockContents->{"title_".@$ActiveLanguage->code} }}" maxlength="191" dir="{{ @$ActiveLanguage->direction }}" class="form-control"/>
                </div>
            </div>
        @endif
    @endforeach
</div>
<div id="field_of_desc" class="{{ (@$TopicBlock->desc_status)?"":"displayNone" }}">
    @foreach(Helper::languagesList() as $ActiveLanguage)
        @if($ActiveLanguage->box_status)
            <div class="form-group row">
                <label for="desc_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.blockDesc') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                </label>
                <div class="col-sm-9">
                    <textarea name="desc_{{ @$ActiveLanguage->code }}" class="form-control" rows="3" dir="{{ @$ActiveLanguage->direction }}">{{ @$TopicBlockContents->{"desc_".@$ActiveLanguage->code} }}</textarea>
                </div>
            </div>
        @endif
    @endforeach
</div>
<div id="field_of_image" class="{{ (@$TopicBlock->image_status)?"":"displayNone" }}">
    @foreach(Helper::languagesList() as $ActiveLanguage)
        @if($ActiveLanguage->box_status)
            <div class="form-group row">
                <label for="photo_{{ @$ActiveLanguage->code }}" class="col-sm-3 form-control-label">{!!  __('backend.blockBackground') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                </label>
                <div class="col-sm-9">
                    @if(@$TopicBlockContents->{"bg_". @$ActiveLanguage->code}!="")
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="block_photo_{{ @$ActiveLanguage->code }}" class="col-sm-6 box p-a-xs">
                                    <a target="_blank"
                                       href="{{ route("fileView",["path" =>'topics/'.@$TopicBlockContents->{"bg_". @$ActiveLanguage->code}]) }}"><img
                                            src="{{ route("fileView",["path" =>'topics/'.@$TopicBlockContents->{"bg_". @$ActiveLanguage->code}]) }}"
                                            class="img-responsive">
                                        {{ @$TopicBlockContents->{"bg_". @$ActiveLanguage->code} }}
                                    </a>
                                    <br>
                                    <a onclick="document.getElementById('block_photo_{{ @$ActiveLanguage->code }}').style.display='none';document.getElementById('photo_{{ @$ActiveLanguage->code }}_delete').value='1';document.getElementById('undo').style.display='block';"
                                       class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                </div>
                                <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                    <a onclick="document.getElementById('block_photo_{{ @$ActiveLanguage->code }}').style.display='block';document.getElementById('photo_{{ @$ActiveLanguage->code }}_delete').value='0';document.getElementById('undo').style.display='none';">
                                        <i class="material-icons">
                                            &#xe166;</i> {!!  __('backend.undoDelete') !!}</a>
                                </div>

                                <input type="hidden" name="photo_{{ @$ActiveLanguage->code }}_delete" value="0" id="photo_{{ @$ActiveLanguage->code }}_delete">
                                <input type="hidden" name="old_photo_{{ @$ActiveLanguage->code }}" value="{{ @$TopicBlockContents->{"bg_". @$ActiveLanguage->code} }}">
                            </div>
                        </div>
                    @endif

                    <input type="file" name="photo_{{ @$ActiveLanguage->code }}" id="photo_{{ @$ActiveLanguage->code }}" class="form-control" accept="image/*">

                </div>
            </div>
        @endif
    @endforeach
</div>
