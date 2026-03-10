<input type="hidden" name="topic_id" class="form-control" value="{{ encrypt($Topic->id) }}">
<input type="hidden" name="block_id" class="form-control" value="{{ @$TopicBlock->id }}">
<input type="hidden" name="content_type" class="form-control" value="0">
<div class="light">
    <div class="row">
        @include("dashboard.topics.landing.blocks.settings")
        <div class="col-sm-9">
            <div class="p-a-2 white b-l">
                <div class="form-group">
                    @include("dashboard.topics.landing.blocks.meta")
                    @foreach(Helper::languagesList() as $ActiveLanguage)
                        @if($ActiveLanguage->box_status)
                            <div class="form-group row">
                                <label for="details_{{ @$ActiveLanguage->code }}"
                                    class="col-sm-12 form-control-label">{!!  __('backend.bannerDetails') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                </label>
                                <div class="col-sm-12">
                                    <div>
                                        @if (Helper::GeneralWebmasterSettings("text_editor") == 2)
                                            <div>
                                                <textarea name="details_{{ @$ActiveLanguage->code }}" id="details_{{ @$ActiveLanguage->code }}" class="form-control ckeditor" dir="{{ @$ActiveLanguage->direction }}">{{ @$TopicBlockContents->{"details_".@$ActiveLanguage->code} }}</textarea>
                                            </div>
                                        @elseif (Helper::GeneralWebmasterSettings("text_editor") == 1)
                                            <div>
                                                <textarea name="details_{{ @$ActiveLanguage->code }}"  id="details_{{ @$ActiveLanguage->code }}" class="form-control tinymce" dir="{{ @$ActiveLanguage->direction }}">{{ @$TopicBlockContents->{"details_".@$ActiveLanguage->code} }}</textarea>
                                            </div>
                                        @else
                                            <div class="box p-a-xs">
                                                <textarea name="details_{{ @$ActiveLanguage->code }}"  id="details_{{ @$ActiveLanguage->code }}" class="form-control summernote summernote_{{ @$ActiveLanguage->code }}" dir="{{ @$ActiveLanguage->direction }}">{{ @$TopicBlockContents->{"details_".@$ActiveLanguage->code} }}</textarea>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    active_content_type = "{{ @$content_type }}";
    moreBtnStatusSettingsView(0);
    @if(Helper::GeneralWebmasterSettings("text_editor") ==1)
    initTinyMCE();
    @endif
</script>
@include('dashboard.layouts.editor',['StopEditorCode'=>1])

