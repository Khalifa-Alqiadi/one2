<input type="hidden" name="topic_id" class="form-control" value="{{ encrypt($Topic->id) }}">
<input type="hidden" name="block_id" class="form-control" value="{{ @$TopicBlock->id }}">
<input type="hidden" name="content_type" class="form-control" value="1">
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
                                <label for="code_{{ @$ActiveLanguage->code }}"
                                    class="col-sm-12 form-control-label">{!!  __('backend.customCode') !!} {!! @Helper::languageName($ActiveLanguage) !!}
                                </label>
                                <div class="col-sm-12">
                                    <div dir="ltr">
                                        <textarea name="code_{{ @$ActiveLanguage->code }}" id="code_{{ @$ActiveLanguage->code }}" class="form-control code-editor" dir="ltr" rows="10">{{ @$TopicBlockContents->{"code_".@$ActiveLanguage->code} }}</textarea>
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

<link rel="stylesheet" href="{{ asset('assets/dashboard/js/codemirror/lib/codemirror.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('assets/dashboard/js/codemirror/theme/eclipse.css') }}" type="text/css"/>
<script src="{{ asset('assets/dashboard/js/codemirror/lib/codemirror.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/addon/hint/show-hint.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/addon/hint/xml-hint.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/addon/hint/html-hint.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/addon/display/autorefresh.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/mode/xml/xml.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/mode/javascript/javascript.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/mode/css/css.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
<script type="text/javascript">
    $(function () {
        var editors = [];
        $('.code-editor').each(function() {
            var editor = CodeMirror.fromTextArea(this, {
                mode: "text/html",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                lineWrapping: true,
                theme: "eclipse",
                autoRefresh: true
            });
            editors.push(editor);
        });
    });
    moreBtnStatusSettingsView(0);
</script>
