<div class="tab-pane  {{ $tab_4 }}" id="tab_code">

    <div class="box-body p-a-2">
        <form method="POST" action="{{ route("WebmasterSectionsCodeUpdate",$WebmasterSections->id) }}" class="dashboard-form">
            @csrf
            <div>
                <div class="form-group">
                    <h6>{!!  __('backend.customCSS') !!}</h6>
                    <div dir="ltr">
                        <textarea name="css_code" id="css_code" class="form-control" dir="ltr" rows="10">{{ @$WebmasterSections->css_code }}</textarea>
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
</script>">{{ @$WebmasterSections->js_code }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <h6>{!!  __('backend.customCodeOnBody') !!}</h6>
                    <div dir="ltr">
                        <textarea name="body_code" id="body_code" class="form-control" dir="ltr" rows="10">{{ @$WebmasterSections->body_code }}</textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-lg btn-primary m-t"><i class="material-icons">
                            &#xe31b;</i> {!! __('backend.update') !!}</button>
                    <a href="{{ route('WebmasterSectionsEdit',$WebmasterSections->id) }}"
                       class="btn btn-lg btn-default m-t"><i class="material-icons">
                            &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                </div>
            </div>
        </form>
    </div>

</div>
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/codemirror/lib/codemirror.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/codemirror/theme/eclipse.css') }}" type="text/css"/>
@endpush
@push("after-scripts")
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
            editor = CodeMirror.fromTextArea(document.getElementById("css_code"), {
                mode: "text/css",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                lineWrapping: true,
                theme: "eclipse",
                autoRefresh: true
            });
            editor2 = CodeMirror.fromTextArea(document.getElementById("js_code"), {
                mode: "text/html",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                lineWrapping: true,
                theme: "eclipse",
                autoRefresh: true
            });
            editor2 = CodeMirror.fromTextArea(document.getElementById("body_code"), {
                mode: "text/html",
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineNumbers: true,
                lineWrapping: true,
                theme: "eclipse",
                autoRefresh: true
            });
        });
    </script>
@endpush
